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

    public function create_address($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->create_address($code);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    // public function get_account_id($code = null){
    //     try{
    //         if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
    //         $res = $this->manager->get_account_id($code);
    //         return ["code" => 200, "data" => $res];
    //     }catch(Exception $e){
    //         return ["code" => 412, "error" => $e->getMessage()];
    //     }
    // }
    
    // public function show_an_account($code){
    //     try{
    //         if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
    //         $res = $this->manager->show_an_account($code);
    //         return ["code" => 200, "data" => $res];
    //     }catch(Exception $e){
    //         return ["code" => 412, "error" => $e->getMessage()];
    //     }
    // }

    // public function list_addresses($code){
    //     try{
    //         if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
    //         $res = $this->manager->list_addresses($code);
    //         return ["code" => 200, "data" => $res];
    //     }catch(Exception $e){
    //         return ["code" => 412, "error" => $e->getMessage()];
    //     }
    // }

    public function list_transactions($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->list_transactions($code);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    
}




?>