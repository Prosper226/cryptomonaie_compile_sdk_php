<?php

namespace MoovAfrica;

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
        $this->app      =   new App('moovafrica');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Moovafrica');
    }

    private function errorFund($res){
        try{ 
            if($res->status != "0"){
                throw new Exception($res->message);
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function checkSubscriber($phone = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            $command = url_recode($this->config['command-id']['checkSubscriber']);
            $body = array ('destination' => $phone, "request-id" => "check_$phone".'_'.time());
            $result = $this->request->make('POST', $body, $command);
            $this->errorFund($result);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function payment($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $command = url_recode($this->config['command-id']['payment']);
            $request_id = ($bash) ? $bash : "payment_$phone".'_'.time().'_'.$amount;
            $body = array (
                "request-id"    => $request_id,
                "destination"   => $phone, 
                "amount"        => $amount,
                "remarks"       => $phone.'_'.time(),
                "message"       => "PAYMENT OF 600 TO ABC PLEASE CONFIRM WITH PIN",
                "extended-data" => [
                    "ext2"          => "CUSTOM STRING",
                    "custommessge"  => "Payment for XXXX"
                ]
            );
            $result = $this->request->make('POST', $body, $command);
            $this->errorFund($result);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function transactionStatus($request_id = null){
        try{
            if(!isset($request_id) || !$request_id) throw new Exception('request_id param is mandatory.');
            $command = url_recode($this->config['command-id']['transactionStatus']);
            $body = array ("request-id" => $request_id);
            $result = $this->request->make('POST', $body, $command);
            $this->errorFund($result);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function transfert($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $command = url_recode($this->config['command-id']['transfert']);
            $request_id = ($bash) ? $bash : "transfert_$phone".'_'.time().'_'.$amount;
            $body = array (
                "request-id"    => $request_id,
                "destination"   => $phone, 
                "amount"        => $amount,
                "remarks"       => $phone.'_'.time(),
                "message"       => "TRANSFERT OF 600 TO ABC PLEASE CONFIRM WITH PIN",
                "extended-data" => [
                    "ext2"          => "CUSTOM STRING",
                    "custommessge"  => "Transfert for XXXX"
                ]
            );
            $result = $this->request->make('POST', $body, $command);
            $this->errorFund($result);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    

}

?>
