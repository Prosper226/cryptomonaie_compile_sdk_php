<?php

namespace MoovCentral;

use Exception;
use lab\App;
use lab\Request;
use stdClass;

use function lab\url_recode;

class Manager{

    private $app = null;
    private $config = null;
    private $business = null;
    private $request = null;
    
    public function __construct($business){
        $this->business =   $business;
        $this->app      =   new App('moovcentral');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Moovcentral');
    }
    
    public function getBalance(){
        try{
            $url = url_recode($this->config['endpoint']['balance']);
            $result = $this->request->make('POST', [], $url);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getHistory($nature = null){
        try{
            if(!isset($nature) || !$nature) throw new Exception('nature param is mandatory.');
            $command = url_recode($this->config['endpoint']['history']);
            $body = array (
                "nature"       => $nature
            );
            $result = $this->request->make('POST', $body, $command);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function payment($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $command = url_recode($this->config['endpoint']['payment']);
            $body = array (
                "tel"       => $phone, 
                "amount"    => $amount,
                "bash"      => $bash
            );
            $result = $this->request->make('POST', $body, $command);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function transfert($phone = null, $amount = 0, $bash = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            $command = url_recode($this->config['endpoint']['transfert']);
            $body = array (
                "tel"       => $phone, 
                "amount"    => $amount,
                "bash"      => $bash
            );
            $result = $this->request->make('POST', $body, $command);
            return $result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    

}

?>
