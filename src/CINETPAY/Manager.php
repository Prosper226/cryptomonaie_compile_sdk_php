<?php

namespace CinetPay;

use Exception;
use lab\App;
use lab\Request;
use CinetPay\Guichet;
use CinetPay\PayService;
use stdClass;

use function lab\url_recode;

class Manager{

    private $app = null;
    private $config = null;
    private $business = null;
    // private $request = null;

    private $endpoint   = null;
    private $isoCountry = null;
    private $apiAuth    = null;
    private $Guichet    = null;
    private $PayService = null;

    public function __construct($business){
        $this->business =   $business;
        $this->app      =   new App('cinetpay');
        $this->config   =   $this->app->getAccess($this->business);
        // $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        // $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Bapi');
        $this->endpoint     = $this->config['APP']['callback'];
        $this->isoCountry   = $this->config['Countries'];
        $this->apiAuth      = $this->config['APP']['api'];
        $this->Guichet      = new Guichet($this->apiAuth['site_id'], $this->apiAuth['apikey']);
        $this->PayService   = new PayService($this->apiAuth['apikey'], $this->apiAuth['apiPassword']);

    }

    public function balance($isoCountry = null){
        try{
            if(empty($isoCountry)){ 
                $balance = $this->PayService->checkBalanceAll();
            }else{
                if(!array_key_exists($isoCountry, $this->isoCountry)) throw new Exception ("$isoCountry is not configured as payment method for $this->business");
                if(!isset($isoCountry) || (empty($isoCountry))) throw new Exception('iso country is required');
                $balance = $this->PayService->checkBalance($isoCountry);
            }
            return $balance;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deposit($isoCountry = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            if(! array_key_exists($isoCountry, $this->isoCountry)) throw new Exception ("$isoCountry is not configured as payment method for $this->business");
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != 3)) throw new Exception('Incomplete input data');
            $formData = array(
                "transaction_id"=> $data['bash'],
                "amount"        => $data['amount'],
                "currency"      => "XOF",
                "description"   => "Paiement mobile vers $this->business",
                "notify_url"    => $this->endpoint['notify_url'],
                "return_url"    => $this->endpoint['return_url'],
                "channels"      => "MOBILE_MONEY",
                "lock_phone_number"     => true,
                "customer_phone_number" => '+'.(string)$this->isoCountry[$isoCountry]['prefix'].$data['phone'],
                "invoice_data" => [], 
                "customer_surname"  => "",
                "customer_name"     => ""
            );
            $result = $this->Guichet ->generatePaymentLink($formData);
            if($result["code"] == '201'){
                $url = $result["data"]["payment_url"];
                $returned = [
                    "transaction_id"    => $formData['transaction_id'],
                    "amount"            => $formData['amount'],
                    "customer_phone_number" => $formData['customer_phone_number'],
                    "payment_url"       => $url
                ];
                return $returned;
            }else{
                throw new Exception($result["code"].' : '.$result["description"]);
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function withdraw($isoCountry = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            if(! array_key_exists($isoCountry, $this->isoCountry)) throw new Exception ("$isoCountry is not configured as payment method for $this->business");
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != 3)) throw new Exception('Incomplete input data');
            $transfer['transfer_id']= $data['bash'];
            $transfer['prefix']     = $this->isoCountry[$isoCountry]['prefix'];
            $transfer['phone']      = $data['phone'];
            $transfer['amount']     = $data['amount'];
            $transfer['notify_url'] = $this->endpoint['notify_url'];
            $transfer['country_iso']= $this->isoCountry[$isoCountry]['iso'];
            $result = $this->PayService->sendMoney($transfer);
            if($result){
                if($result->code != 0){
                    throw new Exception ("$result->code : $result->message => $result->description");
                }
                // $returned = [
                //     "transfer_id"   => $transfer['transfer_id'],
                //     "amount"        => $transfer['amount'],
                //     "phone"         => '+'.$transfer['prefix'].$transfer['phone'],
                // ];
                $returned = $result->data[0][0];
                return $returned;
            }else{
                throw new Exception ('failed_send_money');
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function operationStatus($data = null){
        try{
            if (isset($data['cpm_trans_id'])) {
                try {
                    /* Implementer le HMAC pour une vérification supplémentaire .*/
                    //Etape 1 : Concatenation des informations posté
                    $data_post = implode('',$data);
                    //Etape 2 : Créer le token suivant la technique HMAC en appliquant l'algorithme SHA256 avec la clé secrète
                    $generated_token = hash_hmac('SHA256', $data_post, $this->apiAuth["secret_key"]);
                    if($_SERVER["HTTP_X_TOKEN"]){
                        $xtoken = $_SERVER["HTTP_X_TOKEN"];
                    }else{
                        $xtoken = "indisponible";
                    }
                    //Etape 3: Verifier que le token reçu dans l’en-tête correspond à celui que vous aurez généré.
                    if(hash_equals($xtoken, $generated_token))
                    {
                        // Valid Token
                        $validtoken = True;
                        //Création d'un fichier log pour s'assurer que les éléments sont bien exécuté
                        $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
                            "TransId:".$data['cpm_trans_id'].PHP_EOL.
                            "SiteId: ".$data['cpm_site_id'].PHP_EOL.
                            "HMAC RECU: ".$xtoken.PHP_EOL.
                            "HMAC GENERATE: ".$generated_token.PHP_EOL.
                            "VALID-TOKEN: ".$validtoken.PHP_EOL.
                            "-------------------------".PHP_EOL;
                        file_put_contents(dirname(__DIR__)."/.cinetpay_logs", $log, FILE_APPEND);
                        // on verifie l'état de la transaction en cas de tentative de paiement sur CinetPay
            
                        $this->Guichet->getPayStatus($data['cpm_trans_id'], $this->apiAuth['site_id']);
            
                        $payment_date = $this->Guichet->chk_payment_date;
                        $amount = $this->Guichet->chk_amount;
                        $currency = $this->Guichet->chk_currency;
                        $message = $this->Guichet->chk_message;
                        $code = $this->Guichet->chk_code;
                        $metadata = $this->Guichet->chk_metadata;
            
                        //Enregistrement du statut dans le fichier log
                        $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
                            "Code:".$code.PHP_EOL.
                            "Message: ".$message.PHP_EOL.
                            "Amount: ".$amount.PHP_EOL.
                            "currency: ".$currency.PHP_EOL.
                            "-------------------------".PHP_EOL;
                        //Save string to log, use FILE_APPEND to append.
                        file_put_contents(dirname(__DIR__)."/.cinetpay_logs", $log, FILE_APPEND);
                        // On verifie que le montant payé chez CinetPay correspond à notre montant en base de données pour cette transaction
                        if ($code == '00'){
                            return $data;
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            } else {
                return false;
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}