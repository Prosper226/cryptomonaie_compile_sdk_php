<?php
namespace MoovAfrica;

use lab\App;
use lab\Request;
use Exception;

class Moov {

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

    public function checkSubscriber($phone = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            $res = $this->manager->checkSubscriber($phone);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    
    public function payment($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $res = $this->manager->payment($phone, $amount, $bash);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    
    public function transactionStatus($request_id = null){
        try{
            if(!isset($request_id) || !$request_id) throw new Exception('request_id param is mandatory.');
            $res = $this->manager->transactionStatus($request_id);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    
    public function transfert($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $res = $this->manager->transfert($phone, $amount, $bash);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }


}

?>