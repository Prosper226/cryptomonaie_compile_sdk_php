<?php
namespace MoovCentral;

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

    public function getBalance(){
        try{
            $res = $this->manager->getBalance();
            return $res;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getHistory($nature){
        try{
            $res = $this->manager->getHistory($nature);
            return $res;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function payment($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $res = $this->manager->payment($phone, $amount, $bash);
            return $res;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function transfert($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $res = $this->manager->transfert($phone, $amount, $bash);
            return $res;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }



}

?>