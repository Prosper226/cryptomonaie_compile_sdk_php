<?php
namespace Dunya;

use Exception;

class Dunya{

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

    public function balance(){
        try{
            $res = $this->manager->balance();
            if($res->response_code == "00"){
                return $res->balance;
            }else{
                return false;
            }
            return ["code" => 200, "data" => $res]; 
            return $res;
        }catch(Exception $e){
            return ["code" => 400, "error" => $e->getMessage()];
        }
    }

    public function deposit($country = null, $mobile = null, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null]){
        try{
            $res = $this->manager->deposit($country, $mobile, $data);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }
    
    public function withdraw($country = null, $mobile = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->withdraw($country, $mobile, $data);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }

    public function operationStatus($data = null){
        try{
            return $this->manager->operationStatus($data);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function findByToken($token = null){
        try{
            if(!$token) throw new Exception ("token value is required");
            return $this->manager->findByToken($token);
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return false; // ["code" => 400, "message" => $e->getMessage()];
        }
    }

    


}