<?php

namespace Ligdicash;

use Exception;
use lab\App;
use lab\Request;
use stdClass;

use function lab\url_recode;

class Manager{

    private $app        = null;
    private $config     = null;
    private $business   = null;
    private $request    = null;
    
    public function __construct($business){
        try{
            $this->business =   $business;
            $this->app      =   new App('ligdicash');
            $this->config   =   $this->app->getAccess($this->business);
            $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Ligdicash');
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    private function errorFund($res){
        try{
            if(!isset($res->response_code) or $res->response_code != "00") {
                throw new Exception ($res->response_code.'|#|'.$res->response_text);
            }    
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    // public function payment($data = ["phone" => null, "amount" => null, "bash" => null, "otp" => '']){
    public function payment($data = ['bash' => null, 'phone' => null, 'amount' => null, 'otp' => '']){
        try{
            $callback_url = $this->config['APP']['api']['callback_url'];
            $amount = $data['amount'];
            $phone  = $data['phone'];
            $otp    = isset($data['otp']) ? $data['otp'] : '';
            $bash   = $data['bash'];
            $app_site_name = $this->business;
            $website_url  = "https://www.pmuflash.com";
            $cancel_url  = $callback_url;
            $return_url  = $callback_url;
            $token       = $this->config['APP']['api']['apiToken'];
            $apikey      = $this->config['APP']['api']['apiKey'];
            $nom            =   "Nom du client";
            $prenom         =   "Prenon du client";
            $email          =   "tester@gligdicash.com";
            $description    =   "Description du produit ou Service";
            $transaction_id = $bash;
            
            // $res = $this->Payin_with_redirection($app_site_name,$website_url,$cancel_url,$return_url,$callback_url,$token,$apikey,$nom,$prenom,$email,$description, $transaction_id, $amount, $phone, $otp);

            // $body = [
            //     "commande" =>  [ 
            //         "invoice" => [ 
            //             "items" => [
            //                 [
            //                     "name" => "Nom du produit ou Service",
            //                     "description" => " Description du produit ou Service ", 
            //                     "quantity" => 1,
            //                     "unit_price"  => "$amount",
            //                     "total_price" => "$amount"
            //                 ]
            //             ],
            //             "total_amount" => "$amount",
            //             "devise" => "XOF",
            //             "description" => " Description du contenu de la facture(Achat de jus de fruits)", 
            //             "customer" => "$phone", 
            //             "customer_firstname" => "Nom du client",
            //             "customer_lastname" =>"Prenon du client",
            //             "customer_email" => "tester@gligdicash.com", 
            //             "external_id" =>"",
            //             "otp" => "$otp"
            //         ],
            //         "store" => [
            //             "name" => "Nom de votre site ou de votre boutique",
            //             "website_url" => "https://www.pmuflash.com"
            //         ],
            //         "actions" => [
            //             "cancel_url"  => "$callback_url",
            //             "return_url"  => "$callback_url",
            //             "callback_url"=> "$callback_url"
            //         ], 
            //         "custom_data" => [
            //             "transaction_id" => "$bash"
            //         ]
            //     ]
            // ];

            // return $body;
            // $url = url_recode($this->config['endpoint']['payment_without_redirect']);
            // $res =  $this->request->make('POST', $body, $url);
            // $this->errorFund($res);
            // return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function paymentStatus($token = null){
        try{
            $url = url_recode($this->config['endpoint']['paymentStatus']).$token;
            $res    =  $this->request->make('GET', [], $url);
            $this->errorFund($res);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function Payin_with_redirection($app_site_name,$website_url,$cancel_url,$return_url,$callback_url,
    $token,$apikey,$nom,$prenom,$email,$description,$transaction_id,$amount, $phone, $otp){

        $curl = curl_init();
        $url = $this->config['baseUrl'].url_recode($this->config['endpoint']['payment_without_redirect']);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>'
                                {
                                "commande": {
                                "invoice": {
                                    "items": [
                                    {
                                        "name": "'.$app_site_name.'",
                                        "description": "'.$description.'",
                                        "quantity": 1,
                                        "unit_price": "'.$amount.'",
                                        "total_price": "'.$amount.'"
                                    }
                                    ],
                                    "total_amount": "'.$amount.'",
                                    "devise": "XOF",
                                    "description": "'.$description.'",
                                    "customer": "'.$phone.'",
                                    "customer_firstname":"'.$nom.'",
                                    "customer_lastname":"'.$prenom.'",
                                    "customer_email":"'.$email.'",
                                    "otp" : "'.$otp.'"
                                },
                                "store": {
                                    "name": "'.$app_site_name.'",
                                    "website_url": "'.$website_url.'",
                                },
                                "actions": {
                                    "cancel_url": "'.$cancel_url.'",
                                    "return_url": "'.$return_url.'",,
                                    "callback_url": "'.$callback_url.'"
                                },
                                "custom_data": {
                                    "transaction_id": "'.$transaction_id.'" 
                                }
                                }
                            }',
            CURLOPT_HTTPHEADER => array(
            'Accept'=>'application/json',
            'Content-Type'=>'application/json',
            'Authorization: Bearer '.$token,
            'Apikey: '.$apikey
            ),
        ));

        try{
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        // // $response = json_decode(curl_exec($curl));
        // curl_close($curl);
        // // return $response;
        // return json_decode($response);
    }
    
}

?>
