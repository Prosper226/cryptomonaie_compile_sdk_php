<?php
namespace Crypto;

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

    public function status(){
        try{
            $res = $this->manager->status();
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

    public function operationStatus($data = null){
        try{
            date_default_timezone_set("Africa/Ouagadougou"); 
            $sentence = "Ambition is priceless".':'.$data['transId'].':'.$data['amount'].':'.$data['address'].':'.$data['crypto_hash'].':'.$data['bash'].':'.$data['created_at'].":".date("H");
            $vhash = sha1(sha1(sha1($sentence)));
            return ($vhash !== $data['vhash']) ? false : true;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function callbackReceive($id){
        try{
            if(!isset($id) || !$id) throw new Exception('id param is mandatory.');
            $res = $this->manager->callbackReceive($id);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function history($data = ["type" => null, "startTimestamp" => null, "endTimestamp" => null]){
        try{
            $res = $this->manager->history($data);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }


    public function deposit($coin = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->deposit($coin, $data);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function withdraw($coin = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->withdraw($coin, $data);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function balance($coin = null){
        try{
            if(!isset($coin) || !$coin) throw new Exception('coin param is mandatory.');
            $res = $this->manager->balance($coin);
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }





}