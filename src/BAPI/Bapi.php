<?php
namespace Bapi;

use Exception;

class Bapi{

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

    public function balance($merchant = null){
        try{
            $res = $this->manager->balance($merchant);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function deposit($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->deposit($merchant, $data);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function withdraw($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->withdraw($merchant, $data);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function cancel($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->cancel($bash);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function check($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->check($bash);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }


}