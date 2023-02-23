<?php
namespace Dunya;

use Exception;

class Dunya{

    private $business = null;
    private $manager  = null; 

    public function __construct($business = null){
        try{
            if(!isset($business) || !$business) throw new Exception('app name is mandatory.');
            $this->business  = $business;
            $this->manager   = new Manager($this->business);
        }catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
    }

    public function balance(){
        try{
            $res = $this->manager->balance();
            if($res->response_code == "00"){
                return $res->balance;
            }else{
                return false;
            }
            return ["code" => 200, "data" => $res]; 
            return $res;
        }catch(Exception $e){
            return ["code" => 400, "error" => $e->getMessage()];
        }
    }

    public function deposit($country = null, $mobile = null, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null]){
        try{
            // if(! array_key_exists($country, $this->countries)) throw new Exception ("$country is not configured as payment method for $this->business");
            // if(! array_key_exists($mobile, $this->countries[$country]['mobiles'])) throw new Exception ("$mobile is not configured as payment method for " . $this->countries[$country]['name']. " on $this->business");
            // $mobile_data    = $this->countries[$country]['mobiles'][$mobile];
            // $custom         = ["business" =>  $this->business, "country" => $this->countries[$country]['name'], "mobile" => $mobile];
            $res = $this->manager->deposit($country, $mobile, $data);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }
    
    // public function operationStatus($data = null){
    //     try{
    //         if(isset($data['hash'])) {
    //             try {
    //                 /* Implementer le HASH pour une vérification supplémentaire .*/
    //                 //Etape 1 : Créer le token suivant la technique HASH en appliquant l'algorithme SHA-512 avec la clé principale
    //                 $generated_token = hash('sha512', $this->apiAuth["masterKey"]);
    //                 $xtoken = $data['hash'];
    //                 //Etape 2: Verifier que le token reçu dans la reponse payDuni correspond à celui que nous aurons généré.
    //                 if($xtoken === $generated_token){    
    //                     // on verifie l'état de la transaction en cas de tentative de paiement sur PayDunia
    //                     $invoiceToken = $data['invoice']['token'];
    //                     $payStatus   = $this->manager->getPayStatus($invoiceToken, $this->request);
                        
    //                     if($payStatus->response_code == "00"){
    //                         // On construit un nouveau tableau facilement plus exploitable
    //                         $newArray = [
    //                             "status"                => $payStatus->status,
    //                             "provider_reference"    => $payStatus->provider_reference,
    //                             "receipt_identifier"    => $payStatus->receipt_identifier,
    //                             "customer_phone"        => $payStatus->customer->phone,
    //                             "payment_method"        => $payStatus->customer->payment_method,
    //                             "bash"                  => $payStatus->custom_data->bash,
    //                             "amount"                => $payStatus->invoice->total_amount,
    //                             "token"                 => $payStatus->invoice->token
    //                         ];
    //                         // On notifie l'operation dans fichier des logs
    //                         $log  = 
    //                             "DEPOT: ".$payStatus->invoice->token.' | '."CREATED: ".date("F j, Y, g:i a").' | '."EXPIRED: ".$payStatus->invoice->expire_date.PHP_EOL.
    //                             "FACTURE: ".$payStatus->receipt_url.PHP_EOL.
    //                             "ARRAY: ".json_encode($newArray).PHP_EOL.
    //                             "-------------------------".PHP_EOL;
    //                         file_put_contents(dirname(__DIR__)."/.sys.log", $log, FILE_APPEND);
    //                         return $newArray;
    //                     }else{
    //                         return false;
    //                     }
    //                 }else{
    //                     return false;
    //                 }
    //             } catch (Exception $e) {
    //                 throw new Exception($e->getMessage());
    //             }
    //         } else {
    //             return false;
    //         }
    //     }catch(Exception $e){
    //         throw new Exception($e->getMessage());
    //     }
    // }

    // public function withdraw($country = null, $mobile = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
    //     try{
    //         if(! array_key_exists($country, $this->countries)) throw new Exception ("$country is not configured as payment method for $this->business");
    //         if(! array_key_exists($mobile, $this->countries[$country]['mobiles'])) throw new Exception ("$mobile is not configured as payment method for " . $this->countries[$country]['name']. " on $this->business");
    //         $mobile_data    = $this->countries[$country]['mobiles'][$mobile];
    //         if(!$mobile_data['withdraw']){ throw new Exception ("$mobile not supported transfert on " .$this->countries[$country]['name']); }
    //         $result         = $this->manager->withdraw($data, $mobile_data, $this->request);
    //         return ["code" => 200, "data" => $result]; 
    //     }catch(Exception $e){
    //         // throw new Exception($e->getMessage());
    //         return ["code" => 400, "message" => $e->getMessage()];
    //     }
    // }


}