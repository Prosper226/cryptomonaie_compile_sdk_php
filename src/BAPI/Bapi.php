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

            // $result = (array) $res;
            // $result['message'] = (array) $result['message'];
            // return $result;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function status($merchant = null){
        try{
            $res = $this->manager->status($merchant);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function deposit($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->deposit($merchant, $data);
            // return (array) $res;
            $result = (array) $res;
            $result['message'] = (array) $result['message'];
            return $result;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function withdraw($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            $res = $this->manager->withdraw($merchant, $data);
            // return (array) $res;
            $result = (array) $res;
            $result['message'] = (array) $result['message'];
            return $result;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function cancel($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->cancel($bash);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function check($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->check($bash);
            // return (array) $res;
            $result = (array) $res;
            $result['transaction'] = (array) $result['transaction'];
            return $result;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function history($data = ["type" => null, "startTimestamp" => null, "endTimestamp" => null]){
        try{
            if($data['type'] === 'withdraw'){
                $data['type'] = 'withdrawal';
            }
            $res = $this->manager->history($data);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function smsHistory(){
        try{
            $res = $this->manager->smsHistory();
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function restitutes($data = ["startTimestamp" => null, "endTimestamp" => null]){
        try{
            $res = $this->manager->restitutes($data);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    
    public function zombiesHistory($data = ["startTimestamp" => null, "endTimestamp" => null]){
        try{
            $res = $this->manager->zombiesHistory($data);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function callbackReceive($id){
        try{
            if(!isset($id) || !$id) throw new Exception('id param is mandatory.');
            $res = $this->manager->callbackReceive($id);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function payClub($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            if(!isset($merchant) || !$merchant) throw new Exception('merchant param is mandatory.');
            $res = $this->manager->payClub($merchant, $data);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function operationStatus($data = null){
        try{
            date_default_timezone_set("Africa/Ouagadougou"); 
            $sentence = "Ambition is priceless".':'.$data['transId'].':'.$data['amount'].':'.$data['phone'].':'.$data['orange_transId'].':'.$data['bash'].':'.$data['created_at'].":".date("H");
            $vhash = sha1(sha1(sha1($sentence)));
            return ($vhash !== $data['vhash']) ? false : true;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function askCallback($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->askCallback($bash);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function restoreSms($merchant = null, $debut = null, $fin = null){
        try{
            if(!isset($debut, $fin, $merchant) || !$debut || !$fin || !$merchant) throw new Exception('invalid param is mandatory.');
            $res = $this->manager->restoreSms($merchant, $debut, $fin);
            return (array) $res; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function batteryLevel($merchant = null){
        try{
            if(!isset($merchant) || !$merchant) throw new Exception('invalid param is mandatory.');
            $res = $this->manager->batteryLevel($merchant);
            return (array) $res; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }



    public function smsHistory_v2($limit = 50){
        try{
            $res = $this->manager->smsHistory_v2($limit);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function phoneTransact($phone){
        try{
            $res = $this->manager->phoneTransact($phone);
            return (array) $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    

}