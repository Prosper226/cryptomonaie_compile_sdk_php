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

    /**
     * - Get Deposit Methods
     * - - Retrieve methods available for depositing a particular asset.
     * - - Récupérer les méthodes disponibles pour déposer un actif particulier.
     */
    public function depositMethod($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $res = $this->manager->depositMethod($code);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    /**
     * - Get Deposit Addresses
     * - - Retrieve (or generate a new) deposit addresses for a particular asset and method.
     * - - Récupérez (ou générez une nouvelle) adresses de dépôt pour un actif et une méthode particuliers.
     */
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

    /**
     * - Get Status of Recent Deposits
     * - - Retrieve information about recent deposits made.
     * - - Récupérer des informations sur les dépôts récents effectués.
     */
    public function depositStatus($code, $method){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($method) || !$method) throw new Exception('method param is mandatory.');
            $res = $this->manager->depositStatus($code, $method);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    /**
     * - Get Withdrawal Information
     * - - Retrieve fee information about potential withdrawals for a particular asset, key and amount.
     * 
     */
    public function withdrawInfo($code, $key, $amount){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            if(!isset($key) || !$key) throw new Exception('key param is mandatory.');
            $res = $this->manager->withdrawInfo($code, $key, $amount);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    /**
     * - Withdraw Funds
     * - - Make a withdrawal request.
     */
    public function withdrawFund($code, $key, $amount){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            if(!isset($key) || !$key) throw new Exception('key param is mandatory.');
            $res = $this->manager->withdrawFund($code, $key, $amount);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    /**
     * - Request Withdrawal Cancelation
     * - - Cancel a recently requested withdrawal, if it has not already been successfully processed.
     * @param String $asset  Asset being withdrawn
     * @param String $refid  Withdrawal reference ID
     */
    public function cancelation($asset, $refid){
        try{
            if(!isset($asset) || !$asset) throw new Exception('asset param is mandatory.');
            if(!isset($refid) || !$refid) throw new Exception('refid param is mandatory.');
            $res = $this->manager->cancelation($asset, $refid);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    /**
     * - Request Wallet Transfer
     * - - Transfer from Kraken spot wallet to Kraken Futures holding wallet. Note that a transfer in the other direction must be requested via the Kraken Futures API endpoint.
     */
    public function transfer($asset, $refid){
        try{
            if(!isset($asset) || !$asset) throw new Exception('asset param is mandatory.');
            if(!isset($refid) || !$refid) throw new Exception('refid param is mandatory.');
            $res = $this->manager->cancelation($asset, $refid);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

}




?>