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

    public function payment($phone = null, $amount = 0){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            $res = $this->manager->payment($phone, $amount);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }


}

?>