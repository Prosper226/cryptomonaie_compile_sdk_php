<?php

namespace Coinbase;

use lab\App;
use lab\Request;
use Exception;

class Coinbase {

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

    public function show_current_user(){
        try{
            $res = $this->manager->show_current_user();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function list_accounts(){
        try{
            $res = $this->manager->list_accounts();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function create_address($code, $network = null){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->create_address($code, $network);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function list_transactions($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->list_transactions($code);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function check_transaction($code, $address, $amount){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($address) || !$address) throw new Exception('address param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $res = $this->manager->check_transaction($code, $address, $amount);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function balance($code = null){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->get_account_balance($code);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function send_monney($code, $recepteur, $amount, $memo = null, $idem = null){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($recepteur) || !$recepteur) throw new Exception('destination address is missing.');
            if(!isset($amount) || !$amount) throw new Exception('amount to send is missing.');
            $res = $this->manager->send_monney($code, $recepteur, $amount, $memo, $idem);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

}




?>