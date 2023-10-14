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
                throw new Exception ($res->response_code.'|#|'.$res->response_text.'|#|'.$res->description);
            }    
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function payment($data = ["phone" => null, "amount" => null, "bash" => null, "otp" => '']){
        try{
            $callback_url = $this->config['APP']['api']['callback_url'];
            $amount = $data['amount'];
            $phone  = $data['phone'];
            $otp    = isset($data['otp']) ? $data['otp'] : '';
            $bash   = $data['bash'];
            $body   = [
                "commande" =>  [ 
                    "invoice" => [ 
                        "items" => [
                            [
                                "name" => "Nom du produit ou Service",
                                "description" => " Description du produit ou Service ", 
                                "quantity" => 1,
                                "unit_price"  => "$amount",
                                "total_price" => "$amount"
                            ]
                        ],
                        "total_amount" => "$amount",
                        "devise" => "XOF",
                        "description" => " Description du contenu de la facture(Achat de jus de fruits)", 
                        "customer" => "$phone", 
                        "customer_firstname" => "Nom du client",
                        "customer_lastname" =>"Prenon du client",
                        "customer_email" => "tester@gligdicash.com", 
                        "external_id" =>"",
                        "otp" => "$otp"
                    ],
                    "store" => [
                        "name" => "Nom de votre site ou de votre boutique",
                        "website_url" => "https://www.pmuflash.com"
                    ],
                    "actions" => [
                        "cancel_url"  => "$callback_url",
                        "return_url"  => "$callback_url",
                        "callback_url"=> "$callback_url"
                    ], 
                    "custom_data" => [
                        "transaction_id" => "$bash"
                    ]
                ]
            ];
            $url = url_recode($this->config['endpoint']['payment_without_redirect']);
            $res =  $this->request->make('POST', $body, $url);
            $this->errorFund($res);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function payment_Redirect($data = ["phone" => null, "amount" => null, "bash" => null, "otp" => '']){
        try{
            $callback_url = $this->config['APP']['api']['callback_url'];
            $amount = $data['amount'];
            $phone  = $data['phone'];
            $otp    = isset($data['otp']) ? $data['otp'] : '';
            $bash   = $data['bash'];
            $body   = [
                "commande" =>  [ 
                    "invoice" => [ 
                        "items" => [
                            [
                                "name" => "Nom du produit ou Service",
                                "description" => " Description du produit ou Service ", 
                                "quantity" => 1,
                                "unit_price"  => "$amount",
                                "total_price" => "$amount"
                            ]
                        ],
                        "total_amount" => "$amount",
                        "currency" => "XOF",
                        "description" => " Description du contenu de la facture(Achat de jus de fruits)", 
                        "customer" => "$phone", 
                        "customer_firstname" => "Nom du client",
                        "customer_lastname" =>"Prenon du client",
                        "customer_email" => "tester@gligdicash.com", 
                        "external_id" =>"",
                        "otp" => "$otp"
                    ],
                    "store" => [
                        "name" => "Nom de votre site ou de votre boutique",
                        "website_url" => "https://www.pmuflash.com"
                    ],
                    "actions" => [
                        "cancel_url"  => "$callback_url",
                        "return_url"  => "$callback_url",
                        "callback_url"=> "$callback_url"
                    ], 
                    "custom_data" => [
                        "transaction_id" => "$bash",
                        "logfile" => "202110210048426170b8ea884a9",
                        "developer" => "barkalab_devOps"
                    ]
                ]
            ];
            $url = url_recode($this->config['endpoint']['payment_with_redirect']);
            $res =  $this->request->make('POST', $body, $url);
            $this->errorFund($res);
            return $res;
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

    public function transfert($data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $callback_url = $this->config['APP']['api']['callback_url'];
            $amount = $data['amount'];
            $phone  = $data['phone'];
            $bash   = $data['bash'];
            $body   = [
                "commande" =>  [ 
                    "amount"        =>  "$amount",
                    "description"   =>  "Description of the content of the WITHDRAWAL", 
                    "customer"      =>  "$phone", 
                    "custom_data"   => [
                        "transaction_id" => "$bash"
                    ],
                    "callback_url" =>  "$callback_url",
                    "top_up_wallet" => 0 
                ]
            ];
            $url = url_recode($this->config['endpoint']['withdraw_without_wallet']);
            $res =  $this->request->make('POST', $body, $url);
            // $this->errorFund($res);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function transfertStatus($token = null){
        try{
            $url = url_recode($this->config['endpoint']['withdrawStatus']).$token;
            $res    =  $this->request->make('GET', [], $url);
            $this->errorFund($res);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}

?>
