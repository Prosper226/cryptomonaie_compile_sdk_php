<?php

namespace Intouch;

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
    private $country    = null;
    private $agence     = null;
    private $mobiles    = null;

    public function __construct($business, $country){
        $this->business =   $business;
        $this->country  =   $country;
        $this->app      =   new App('intouch');
        $this->config   =   $this->app->getAccess($this->country);
        if(!isset($this->config['CLIENT'][$business])){ throw new Exception("$business is not configured as client");}
        $this->config['CLIENT'] = $this->config['CLIENT'][$business];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Intouch');
        $this->agence   =   $this->config['APP']['api']['agence'];
        $this->mobiles  =   $this->config['APP']['mobiles'];
        // print_r($this->config);
    }


    public function balance(){
        try{
            $url    =   url_recode($this->config['endpoint']['balance'], [$this->agence]);
            $res    =   $this->request->make('POST', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function status(){
        try{
            $url    =   url_recode($this->config['endpoint']['status'], [$this->agence]);
            $res    =   $this->request->make('POST', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function withdraw($mobile = null, $data = ["phonenumber" => null, "amount" => null, "txId" => null]){
        try{
            if(!isset($mobile) || !$mobile) throw new Exception('mobile param is required');
            if(!isset($data['phonenumber'], $data['amount'], $data['txId']) || (count($data) != 3)) throw new Exception('invalid payload data');
            if(! array_key_exists($mobile, $this->mobiles)) throw new Exception ("$mobile is not configured as payment method for $this->country");
            $callback = $this->getCallbackUrl($mobile, 'withdraw');
            $mobile = $this->mobiles[$mobile];
            $body = [
                "service_id"              =>   $mobile['withdraw'],
                "recipient_phone_number"  =>   $data['phonenumber'],
                "amount"                  =>   $data['amount'],
                "partner_transaction_id"  =>   $data['txId'],
                "call_back_url"           =>   $callback
            ];
            $url    =   url_recode($this->config['endpoint']['withdraw'], [$this->agence]);
            $result =   $this->request->make('POST', $body, $url);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deposit($mobile = null, $data = ["phonenumber" => null, "amount" => null, "txId" => null, "otp" => null]){
        try{
            if(!isset($data['phonenumber'], $data['amount'], $data['txId']) || (count($data) != 3 && count($data) != 4)) throw new Exception('Incomplete input data');
            if(count($data) == 4 && !isset($data['otp'])) throw new Exception('Incomplete input data');
            if(! array_key_exists($mobile, $this->mobiles)) throw new Exception ("$mobile is not configured as payment method for $this->country");

            $additionnalInfos = [
                "recipientEmail"      =>   'johndoe@domain.extension',
                "recipientFirstName"  =>   'John',
                "recipientLastName"   =>   'Doe',
                "destinataire"        =>   $data['phonenumber'],
            ];
            if(isset($data['otp'])) $additionnalInfos['otp'] = $data['otp'];

            $callback = $this->getCallbackUrl($mobile, 'deposit');
            $mobile   = $this->mobiles[$mobile];

            $body    =   [
                "serviceCode"         =>   $mobile['deposit'],
                "idFromClient"        =>   $data['txId'],
                "additionnalInfos"    =>   $additionnalInfos,
                "amount"              =>   $data['amount'],
                "recipientNumber"     =>   $data['phonenumber'],
                "callback"            =>   $callback,
            ];
            $url  =   url_recode($this->config['endpoint']['deposit'], [$this->agence]);;
            $url .= "?loginAgent=".$this->config['APP']['api']['login_api']."&passwordAgent=".$this->config['APP']['api']['password_api'];
            $result      =   $this->request->make('PUT', $body, $url);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function operationStatus($mobile = null, $type = null, $data = null){
        try{
            if(! array_key_exists($mobile, $this->mobiles)) throw new Exception ("$mobile is not configured as payment method for $this->country");
            if(!isset($data['service_id'], $data['call_back_url'])) throw new Exception('invalid payload data');
            if(!isset($type) || !$type) throw new Exception('invalid payload data');
            
            $type = strtolower($type);
            if(!in_array($type, ['deposit', 'withdraw'])) throw new Exception('invalid type provided');
            
            $callback = $this->getCallbackUrl($mobile, $type);
            $mobile   = $this->mobiles[$mobile];

            $service_id     = $mobile[$type];
            $call_back_url  = $callback;
            
            
            if($data["service_id"] != $service_id || $data["call_back_url"] != $call_back_url){
                throw new Exception("The callback : ".json_encode($data)." seems incorrect");
            } 
            
            return ($data["status"] == "SUCCESSFUL") ? $data : false;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


    // PRIVATES FUNCTION TO GET BEST CALLBACK URL

    private function getCallbackUrl($mobile, $type){
        try{
            $client = $this->config['CLIENT'];
            $throw_message = 'no callback url is configured.';
            if(!isset($client['callback']) || !$client['callback']) throw new Exception($throw_message);
            $callback = $client['callback'];
            // verifier si ya une config pour le pays sinon renvoyer la base
            if(!isset($callback[$this->country]) || !$callback[$this->country]){
                return $this->g_base($callback);
            }
            // si ya configuration pour le payer voir si config pour le type
            if(!isset($callback[$this->country][$type]) || !$callback[$this->country][$type]){
                return $this->c_base($callback);
            }
            // verifier config cote  mobile
            if(!isset($callback[$this->country][$type][$mobile]) || !$callback[$this->country][$type][$mobile] ){
                if(!isset($callback[$this->country][$type]['base']) || !$callback[$this->country][$type]['base']){
                    return $this->c_base($callback);
                }else{
                    return $callback[$this->country][$type]['base'];
                }
            }
            return $callback[$this->country][$type][$mobile];
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function g_base($callback){
        $throw_message = 'no callback url is configured.';
        if(!isset($callback['base']) || !$callback['base']){
            throw new Exception($throw_message);
        }else{
            return $callback['base'];
        }
    }

    private function c_base($callback){
        if(!isset($callback[$this->country]['base']) || !$callback[$this->country]['base']){
            return $this->g_base($callback);
        }else{
            return $callback[$this->country]['base'];
        }
    }

}




?>
