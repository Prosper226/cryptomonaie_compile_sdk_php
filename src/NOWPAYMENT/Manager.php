<?php

namespace Nowpayment;

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
        $this->app      =   new App('nowpayment');
        $this->config   =   $this->app->getAccess($this->business);
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Nowpayment');
    }

    public function system_status(){
        try{
            $url = url_recode($this->config['endpoint']['apiStatus']);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function availableCurrencies(){
        try{
            $url = url_recode($this->config['endpoint']['availableCurrencies']);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function estimetedPrice($amount ,$currency_from, $currency_to){
        try{
            if(!isset($amount ,$currency_from, $currency_to)) {throw new Exception("invalid parameters entry");}
            $url = url_recode($this->config['endpoint']['estimetedPrice'])."?amount=$amount&currency_from=$currency_from&currency_to=$currency_to";
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function minPaymentAmount(){
        try{
            $url = url_recode($this->config['endpoint']['minPaymentAmount']);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}