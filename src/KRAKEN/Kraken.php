<?php

namespace Kraken;

use Exception;

class Kraken {

    private $business = null;
    private $manager  = null; 

    public function __construct($business = null){
        try{
            if(!isset($business) || !$business) throw new Exception('app name is mandatory.');
            $this->business  = $business;
            $this->manager   = new Manager($this->business);
        }catch(Exception $e){
            echo 'Error: '.$e->getMessage();exit;
        }
    }

    public function getBalance(){
        try{
            $res = $this->manager->getBalance();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function depositMethod($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->depositMethod($code);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function newAddress($code, $method){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($method) || !$method) throw new Exception('method param is mandatory.');
            $res = $this->manager->newAddress($code, $method);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

}




?>