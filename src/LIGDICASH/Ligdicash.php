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

    // paiement
    public function deposit($data = ['phone' => null, 'amount' => null, 'bash' => null, 'otp' => '']){
        try{
            $res = $this->manager->payment($data);
            $res = [
                "token"             => $res->token,
                "transaction_id"    => $res->custom_data->transaction_id
            ];
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function deposit_withRedirect($data = ['phone' => null, 'amount' => null, 'bash' => null, 'otp' => '']){
        try{
            $res = $this->manager->payment_Redirect($data);
            $res = [
                "token"             => $res->token,
                "transaction_id"    => $res->custom_data->transaction_id,
                "payment_link"      => $res->response_text
            ];
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    private function depositStatus($token = null){
        try{
            if(!isset($token) || !$token) throw new Exception('token param is mandatory.');
            $res = $this->manager->paymentStatus($token);
            $res = [
                "token"         => $token,
                "status"        => $res->status,        // completed || pending
                "customer"      => $res->customer,
                "montant"       => $res->montant,
                "external_id"   => $res->external_id,
                "id_invoice"    => $res->custom_data[0]->id_invoice
            ];
            $code = ($res['status'] === "completed") ? 200 : 300;
            return  ["code" => $code, "data" => $res];
        }catch(Exception $e){
            // return ["code" => 412, "error" => $e->getMessage()];
            throw new Exception($e->getMessage());
        }
    }

    // transfert
    public function withdraw($data = ['phone' => null, 'amount' => null, 'bash' => null, 'otp' => '']){
        try{
            $res = $this->manager->transfert($data);
            // $res = [
            //     "token"             => $res->token,
            //     "transaction_id"    => $res->custom_data->transaction_id
            // ];
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    private function withdrawStatus($token = null){
        try{
            if(!isset($token) || !$token) throw new Exception('token param is mandatory.');
            $res = $this->manager->transfertStatus($token);
            $res = [
                "token"         => $res->token,
                "status"        => $res->status,        // completed || pending || nocompleted
                "operator_id"   => $res->operator_id,
                "operator_name" => $res->operator_name
            ];
            $code = ($res['status'] === "completed") ? 200 : 300;
            return  ["code" => $code, "data" => $res];
        }catch(Exception $e){
            // return ["code" => 412, "error" => $e->getMessage()];
            throw new Exception($e->getMessage());
        }
    }

    public function operationStatus($token = null, $type = 'deposit' | 'withdraw'){
        try{
            if(!isset($token) || !$token) throw new Exception('token param is mandatory.');
            if(!isset($type) || !$type) throw new Exception('type param is mandatory.');
            switch($type){
                case 'deposit'  : return $this->depositStatus($token);
                case 'withdraw' : return $this->withdrawStatus($token);
                default: throw new Exception ('invalid type value');
            }
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }




}


?>