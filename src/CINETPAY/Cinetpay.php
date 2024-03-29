<?php
namespace CinetPay;

use Exception;

class Cinetpay{

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
    
    public function balance($isoCountry = null){
        try{
            $res = $this->manager->balance($isoCountry);
            return ["code" => 200, "balance" => $res];
        }catch(Exception $e){
            return ["code" => 412, "message" => $e->getMessage()];
        }
    }

    public function deposit($isoCountry = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->deposit($isoCountry, $data);
            return ['code' => 200, 'data' => $res];
        }catch(Exception $e){
            return ["code" => 412, "message" => $e->getMessage()];
        }
    }

    public function withdraw($isoCountry = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->withdraw($isoCountry, $data);
            return ['code' => 200, 'data' => $res];
        }catch(Exception $e){
            return ["code" => 412, "message" => $e->getMessage()];
        }
    }

    public function operationStatus($data = null){
        try{
            $res = $this->manager->operationStatus($data);
            return $res;
        }catch(Exception $e){
            return false;
        }
    }


}