<?php

namespace Dunya;

use Exception;
use lab\App;
use lab\Request;
use stdClass;

use function lab\url_recode;

class Manager{

    private $app = null;
    private $config = null;
    private $business = null;
    private $request = null;
    private $countries = null;
    
    public function __construct($business){
        $this->business =   $business;
        $this->app      =   new App('paydunya');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Paydunya');
        $this->countries =  $this->config['Countries'];
    }

    public function balance($merchant = null){
        try{
            $url = url_recode($this->config['endpoint']['balance']);
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function getInvoiceToken($nature = null, $data = ["phone" => null, "amount" => null, "bash" => null], Array $mobile = [], $custom = ["business" => null, "country" => null, "mobile" => null]){
        try{
            if($nature == 'deposit'){
                if(!isset($data['phone'], $data['amount'], $data['bash'])) throw new Exception('Incomplete input data');
                $notify_url = ($mobile['notify']) ? $mobile['notify'] : $this->config['APP']['notifyUrl'];
                $notify_url = url_recode($notify_url);
                $body = [
                    "invoice" => [
                        "total_amount" => $data['amount'],
                        "description" => "Paiement vers ".$custom['business']." (".$custom['mobile']." - ". $custom['country'].")"
                    ],
                    "store" => [
                        "name" => $custom['business'],
                    ],
                    "custom_data" => $data,
                    "actions" => [
                        "callback_url" => $notify_url
                    ]
                ];
                $url    =   url_recode($this->config['endpoint']['depositToken']);
                $result =   $this->request->make('POST', $body, $url);
                if($result->response_code == '00'){
                    return $result->token;
                }else{
                    throw new Exception($result->response_text);
                }
            }elseif($nature == 'withdraw'){
                if(!isset($data['phone'], $data['amount'])) throw new Exception('Incomplete input data');
                $withdraw_mode = $mobile['withdraw'];
                $body = [
                    "account_alias" =>  $data['phone'], 
                    "amount"        =>  $data['amount'], 
                    "withdraw_mode" =>  $withdraw_mode 
                ];
                $url    =   url_recode($this->config['endpoint']['withdrawToken']);
                $result =   $this->request->make('POST', $body, $url);
                if($result->response_code == '00'){
                    return $result->disburse_token;
                }else{
                    throw new Exception($result->response_text);
                }
            }else{
                throw new Exception ('Unknown operation requested');
            }
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function deposit($country = null, $mobile = null, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null]){
        try{
            $custom = ["business" => $this->business, "country" => $country, "mobile" => $mobile];
            // verifier le pays
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}
            // verifier le mobile
            if(!isset($this->countries[$country]['mobiles'][$mobile])){throw new Exception('Unknow mobile');}
            // verifier necessite de otp
            $mobile = $this->countries[$country]['mobiles'][$mobile];
            $fieldCount = 3; // correspond a la taille normal par defaut de $data
            if($mobile['otp']){ 
                // check if mobile need or no an otp  code
                if(!isset($data['otp']) || empty($data['otp'])) throw new Exception('otp is required');
                $fieldCount = 4;
            }
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != $fieldCount)) throw new Exception('Incomplete input data');
            $nature = 'deposit';
            $invoiceToken = $this->getInvoiceToken($nature, $data, $mobile, $custom);
            $bodyKeys = array_keys($mobile['payload']);
            $body = [
                $bodyKeys[0] => $mobile['payload'][$bodyKeys[0]],   // name
                $bodyKeys[1] => $mobile['payload'][$bodyKeys[1]],   // email 
                $bodyKeys[2] => (String)$data['phone'],             // phone
                $bodyKeys[3] => $invoiceToken                       // token
            ];
            if($mobile['length'] > 4){ 
                // code otp ou wallet_provider pour mtn 
                if($mobile['otp']){
                    // Cas ou il s'agit d'un code otp
                    $body[$bodyKeys[4]] = $data['otp']; 
                }else{
                    // Cas ou il s'agit d'un wallet_providers
                    $body[$bodyKeys[4]] = $mobile['payload'][$bodyKeys[4]]; 
                }
            }
            // error_log(print_r($body, true));
            $url    =   url_recode($this->config['endpoint']['deposit'] , [ $mobile['deposit']] );
            $result =   $this->request->make('POST', $body, $url);

            $result->token = $invoiceToken;
         
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    private function getPayStatus($invoiceToken = null){  // status depot
        try{
            if(!isset($invoiceToken) || empty($invoiceToken)) throw new Exception('invoice token is required');
            $url    =   url_recode($this->config['endpoint']['getPayStatus'] , [ $invoiceToken ] );
            $result =   $this->request->make('GET', null, $url);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function withdraw($country, $mobile, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            // verifier le pays
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}
            // verifier le mobile
            if(!isset($this->countries[$country]['mobiles'][$mobile])){throw new Exception('Unknow mobile');}
            $mobile = $this->countries[$country]['mobiles'][$mobile];
            // verifier si le mobile supporte les retraits.
            if(!$mobile['withdraw']){throw new Exception ("mobile not supported transfert.");}
            $fieldCount = 3; // correspond a la taille normal par defaut de $data
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != $fieldCount)) throw new Exception('Incomplete input data');
            $nature = 'withdraw';
            $invoiceToken = $this->getInvoiceToken($nature, $data, $mobile);
            $body = [
                "disburse_invoice"  => $invoiceToken,
                "disburse_id"       => $data['bash']
            ];
            $url    =   url_recode($this->config['endpoint']['withdraw']);
            $result =   $this->request->make('POST', $body, $url);

            if($result->response_code == '00'){
                $pushStatus = $this->getPushStatus($invoiceToken);
                if($pushStatus->response_code == '00'){
                    if(isset($pushStatus->disburse_tx_id)){
                        $newArray = [
                            "status"                => $pushStatus->status,
                            "bash"                  => $pushStatus->disburse_tx_id,
                            "amount"                => $pushStatus->amount,
                            "token"                 => $pushStatus->token,
                            "withdraw_mode"         => $pushStatus->withdraw_mode,
                            "transaction_id"        => $pushStatus->transaction_id
                        ];
                        $log  = 
                        "RETRAIT: ".$pushStatus->token.' | '."CREATED: ".$pushStatus->updated_at.PHP_EOL.
                        "TRANSID: ".$pushStatus->transaction_id.PHP_EOL.
                        "ARRAY: ".json_encode($newArray).PHP_EOL.
                        "-------------------------".PHP_EOL;
                        file_put_contents(dirname(__DIR__, 1)."/.sys.log", $log, FILE_APPEND);
                        return $newArray;
                    }else{
                        throw new Exception ("Invalid status funding ($pushStatus->status)");
                    }
                }else{
                    throw new Exception ('$result->response_text:getPushStatus');
                }
            }else{
                throw new Exception ($result->response_text);
            }
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    private function getPushStatus($invoiceToken = null){ // status retrait
        try{
            if(!isset($invoiceToken) || empty($invoiceToken)) throw new Exception('invoice token is required');
            $body = [
                "disburse_invoice" => $invoiceToken
            ];
            $url    =   url_recode($this->config['endpoint']['getPushtatus']);
            $result =   $this->request->make('POST', $body, $url);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function operationStatus($data = null){
        try{
            if(isset($data['hash'])) {
                try {
                    /* Implementer le HASH pour une vérification supplémentaire .*/
                    //Etape 1 : Créer le token suivant la technique HASH en appliquant l'algorithme SHA-512 avec la clé principale
                    $generated_token = hash('sha512', $this->config['APP']['api']["masterKey"]);
                    $xtoken = $data['hash'];
                    //Etape 2: Verifier que le token reçu dans la reponse payDuni correspond à celui que nous aurons généré.
                    if($xtoken === $generated_token){    
                        // on verifie l'état de la transaction en cas de tentative de paiement sur PayDunia
                        $invoiceToken = $data['invoice']['token'];
                        $payStatus   = $this->getPayStatus($invoiceToken);
                    
                        if($payStatus->response_code == "00"){
                            // On construit un nouveau tableau facilement plus exploitable
                            $newArray = [
                                "status"                => $payStatus->status,
                                "provider_reference"    => $payStatus->provider_reference,
                                "receipt_identifier"    => $payStatus->receipt_identifier,
                                "customer_phone"        => $payStatus->customer->phone,
                                "payment_method"        => $payStatus->customer->payment_method,
                                "bash"                  => $payStatus->custom_data->bash,
                                "amount"                => $payStatus->invoice->total_amount,
                                "token"                 => $payStatus->invoice->token
                            ];
                            // On notifie l'operation dans fichier des logs
                            $log  = 
                                "DEPOT: ".$payStatus->invoice->token.' | '."CREATED: ".date("F j, Y, g:i a").' | '."EXPIRED: ".$payStatus->invoice->expire_date.PHP_EOL.
                                "FACTURE: ".$payStatus->receipt_url.PHP_EOL.
                                "ARRAY: ".json_encode($newArray).PHP_EOL.
                                "-------------------------".PHP_EOL;
                            file_put_contents(dirname(__DIR__)."/.sys.log", $log, FILE_APPEND);
                            return $newArray;
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

    public function findByToken($token = null){
        try{
            if(!$token) throw new Exception ("token value is required");
            $payStatus = $this->getPayStatus($token);
            if($payStatus->response_code == "00"){
                $newArray = [
                    "status"                => $payStatus->status,
                    "provider_reference"    => $payStatus->provider_reference,
                    "receipt_identifier"    => $payStatus->receipt_identifier,
                    "customer_phone"        => $payStatus->customer->phone,
                    "payment_method"        => $payStatus->customer->payment_method,
                    "bash"                  => $payStatus->custom_data->bash,
                    "amount"                => $payStatus->invoice->total_amount,
                    "token"                 => $payStatus->invoice->token
                ];
                return $newArray;
            }else{
                return false;
            }
            return false; // ["code" => 200, "data" => $result]; 
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return false; // ["code" => 400, "message" => $e->getMessage()];
        }
    }

}