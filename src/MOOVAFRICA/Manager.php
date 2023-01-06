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
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Moovafrica');
    }

    
    public function checkSubscriber($phone = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            $command = url_recode($this->config['command-id']['payment']);
            $body = array ('destination' => $phone, "request-id" => "check_$phone".time());
            $result = $this->request->make('POST', $body, $command);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    
    public function payment($phone = null, $amount = 0){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            $command = url_recode($this->config['command-id']['payment']);
            $body = array (
                "request-id" => "payment_$phone".'_'.time(),
                'destination' => $phone, 
                'amount'    => $amount,
                "remarks"   => $phone.'_'.time(),
                "message" => "PAYMENT OF 600 TO ABC PLEASE CONFIRM WITH PIN",
                "extended-data" => [
                    "ext2" => "CUSTOM STRING",
                    "custommessge" => "Payment for XXXX"
                ]
            );
            $result = $this->request->make('POST', $body, $command);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


}

?>
