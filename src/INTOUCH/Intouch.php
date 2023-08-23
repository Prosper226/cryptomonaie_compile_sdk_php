<?php
namespace Intouch;

use Exception;

class Intouch{

    private $business = null;
    private $manager  = null; 
    private $country  = null;

    public function __construct($business = null, $country = null){
        try{
            if(!isset($business) || !$business) throw new Exception('app name is mandatory.');
            if(!isset($country) || !$country) throw new Exception('country param is missing.');
            $this->business  = $business;
            $this->country   = $country;
            $this->manager   = new Manager($this->business, $this->country);
        }catch(Exception $e){
            echo 'Error: '.$e->getMessage();
        }
    }

    public function balance(){
        try{
            $res = $this->manager->balance();
            if(!$res) throw new Exception('no data from intouch');
            if($res['errorCode'] != 200){ throw new Exception($res['errorCode'].' : '.$res['errorMessage']); }
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    private function status(){
        try{
            $res = $this->manager->status();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function withdraw($mobile = null, $data = ["phonenumber" => null, "amount" => null, "txId" => null]){
        try{
            if(!isset($mobile) || !$mobile) throw new Exception('mobile param is required');
            if(!isset($data['phonenumber'], $data['amount'], $data['txId']) || (count($data) != 3)) throw new Exception('invalid payload data');
            $res = $this->manager->withdraw($mobile, $data);
            if(!$res) throw new Exception('no data from intouch');
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function deposit($mobile = null, $data = ["phonenumber" => null, "amount" => null, "txId" => null, "otp" => null]){
        try{
            if(!isset($mobile) || !$mobile) throw new Exception('mobile param is required');
            if(!isset($data['phonenumber'], $data['amount'], $data['txId'])) throw new Exception('invalid payload data');
            $res = $this->manager->deposit($mobile, $data);
            if(!$res) throw new Exception('no data from intouch');
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function operationStatus($mobile = null, $type = null, $data = null){
        try{
            if(!$mobile || !$type || !$data) throw new Exception('invalid param is required');
            $res = $this->manager->operationStatus($mobile, $type, $data);
            return $res;
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return false;
        }
    }



}







?>