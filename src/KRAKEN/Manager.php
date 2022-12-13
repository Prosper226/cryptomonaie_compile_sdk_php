<?php

namespace Kraken;

use Exception;
use lab\App;
use lab\Request;
use stdClass;

use function lab\url_recode;

class Manager{

    private $app        = null;
    private $config     = null;
    private $business   = null;
    private $request    = null;
    
    public function __construct($business){
        try{
            $this->business =   $business;
            $this->app      =   new App('kraken');
            $this->config   =   $this->app->getAccess($this->business);
            $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Kraken');
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    public function getBalance(){
        try{
            $url = url_recode($this->config['endpoint']['balance']);
            $res = $this->request->make('POST', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


    public function depositMethod($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $url = url_recode($this->config['endpoint']['depositMethod']);
            $res = $this->request->make('POST', ['asset' => $code], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function newAddress($code, $method){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($method) || !$method) throw new Exception('method param is mandatory.');
            $url = url_recode($this->config['endpoint']['newAddress']);
            $body = [
                'asset'  => $code,
                'method' => $method,
                'new'    => 1
            ];
            $res = $this->request->make('POST', $body, $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}
?>