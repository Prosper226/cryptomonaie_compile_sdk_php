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
    
    public function __construct($business){
        $this->business =   $business;
        $this->app      =   new App('paydunya');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Paydunya');
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

    private function getInvoiceToken($nature = null, $data = ["phone" => null, "amount" => null, "bash" => null], Array $mobile = [], Request $request = null, $custom = ["business" => null, "country" => null, "mobile" => null]){
        try{
            if($nature == 'deposit'){
                if(!isset($data['phone'], $data['amount'], $data['bash'])) throw new Exception('Incomplete input data');
                $notify_url = ($mobile['notify']) ? $mobile['notify'] : $this->endpoint['notify'];
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
                $url    =   $this->cashUrl['depositToken'];
                $result =   $request->make('POST', $body, $url);
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
                $url    =   $this->cashUrl['withdrawToken'];
                $result =   $request->make('POST', $body, $url);
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

    public function deposit($country, $mobile, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null]){
        try{
            $fieldCount = 3; // correspond a la taille normal par defaut de $data
            if($mobile['otp']){ 
                // check if mobile need or no an otp  code
                if(!isset($data['otp']) || empty($data['otp'])) throw new Exception('otp is required');
                $fieldCount = 4;
            }
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != $fieldCount)) throw new Exception('Incomplete input data');
            $nature = 'deposit';
            $invoiceToken = $this->getInvoiceToken($nature, $data, $mobile, $request, $custom);
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
            error_log(print_r($body, true));
            $url    =   $mobile['deposit'];
            $result =   $request->make('POST', $body, $url);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    // public function getPayStatus($invoiceToken = null, Request $request = null){
    //     try{
    //         if(!isset($invoiceToken) || empty($invoiceToken)) throw new Exception('invoice token is required');
    //         $url    =   $this->endpoint['getPayStatus'].'/'.$invoiceToken;
    //         $result =   $request->make('GET', null, $url);
    //         return $result;
    //     }catch(Exception $e) {
    //         throw new Exception ($e->getMessage());
    //     }
    // }

    // public function withdraw($data = ["phone" => null, "amount" => null, "bash" => null], Array $mobile = [], Request $request = null){
    //     try{
    //         $fieldCount = 3; // correspond a la taille normal par defaut de $data
    //         if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != $fieldCount)) throw new Exception('Incomplete input data');
    //         $nature = 'withdraw';
    //         $invoiceToken = $this->getInvoiceToken($nature, $data, $mobile, $request);
    //         $body = [
    //             "disburse_invoice"  => $invoiceToken,
    //             "disburse_id"       => $data['bash']
    //         ];
    //         $url    =   $this->cashUrl['withdraw'];
    //         $result =   $request->make('POST', $body, $url);
    //         if($result->response_code == '00'){
    //             $pushStatus = $this->getPushStatus($invoiceToken, $request);
    //             if($pushStatus->response_code == '00'){
    //                 if(isset($pushStatus->disburse_tx_id)){
    //                     $newArray = [
    //                         "status"                => $pushStatus->status,
    //                         "bash"                  => $pushStatus->disburse_tx_id,
    //                         "amount"                => $pushStatus->amount,
    //                         "token"                 => $pushStatus->token,
    //                         "withdraw_mode"         => $pushStatus->withdraw_mode,
    //                         "transaction_id"        => $pushStatus->transaction_id
    //                     ];
    //                     $log  = 
    //                     "RETRAIT: ".$pushStatus->token.' | '."CREATED: ".$pushStatus->updated_at.PHP_EOL.
    //                     "TRANSID: ".$pushStatus->transaction_id.PHP_EOL.
    //                     "ARRAY: ".json_encode($newArray).PHP_EOL.
    //                     "-------------------------".PHP_EOL;
    //                     file_put_contents(dirname(__DIR__, 1)."/.sys.log", $log, FILE_APPEND);
    //                     return $newArray;
    //                 }else{
    //                     throw new Exception ("Invalid status funding ($pushStatus->status)");
    //                 }
    //             }else{
    //                 throw new Exception ('$result->response_text');
    //             }
    //         }else{
    //             throw new Exception ($result->response_text);
    //         }
    //     }catch(Exception $e) {
    //         throw new Exception ($e->getMessage());
    //     }
    // }

    // private function getPushStatus($invoiceToken = null, Request $request = null){
    //     try{
    //         if(!isset($invoiceToken) || empty($invoiceToken)) throw new Exception('invoice token is required');
    //         $body = [
    //             "disburse_invoice" => $invoiceToken
    //         ];
    //         $url    =   $this->endpoint['getPushtatus'];
    //         $result =   $request->make('POST', $body, $url);
    //         return $result;
    //     }catch(Exception $e) {
    //         throw new Exception ($e->getMessage());
    //     }
    // }


}