<?php

namespace Ligdicash;

use Exception;

class Ligdicash {

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

    public function payment($data = ['phone' => null, 'amount' => null, 'bash' => null, 'otp' => '']){
        try{
            $res = $this->manager->payment($data);
            // $res = [
            //     "token"             => $res->token,
            //     "transaction_id"    => $res->custom_data->transaction_id
            // ];
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function paymentStatus($token = null){
        try{
            if(!isset($token) || !$token) throw new Exception('token param is mandatory.');
            $res = $this->manager->paymentStatus($token);
            // $res = [
            //     "token"     => $res->token,
            //     "status"    => $res->status,
            //     "customer"  => $res->customer,
            //     "montant"   => $res->montant,
            //     "external_id" => $res->external_id
            // ];
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

}


?>