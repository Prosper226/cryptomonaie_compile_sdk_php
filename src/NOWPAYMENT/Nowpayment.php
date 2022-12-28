<?php
namespace Nowpayment;

use Exception;

class Nowpayment{

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

    public function system_status(){
        try{
            $res = $this->manager->system_status();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function currencies(){
        try{
            $res = $this->manager->availableCurrencies();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function estimetedPrice($amount ,$currency_from, $currency_to){
        try{
            if(!isset($amount ,$currency_from, $currency_to)) {throw new Exception("invalid parameters entry");}
            $res = $this->manager->estimetedPrice($amount ,$currency_from, $currency_to);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function minPaymentAmount(){
        try{
            $res = $this->manager->minPaymentAmount();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }




}