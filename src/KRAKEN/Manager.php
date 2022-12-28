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

    private function errorFund($res){
        try{
            // ERROR_DOCS: https://support.kraken.com/hc/en-us/articles/360001491786-API-error-messages
            if($res->error){ throw new Exception(implode("#", $res->error));}
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getBalance(){
        try{
            $url = url_recode($this->config['endpoint']['balance']);
            $res = $this->request->make('POST', [], $url);
            $this->errorFund($res);
            return $res->result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function depositMethod($code){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $url = url_recode($this->config['endpoint']['depositMethod']);
            $res = $this->request->make('POST', ['asset' => $code], $url);
            $this->errorFund($res);
            return $res->result;
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
            $this->errorFund($res);
            return $res->result;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function depositStatus($code, $method){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($method) || !$method) throw new Exception('method param is mandatory.');
            $url = url_recode($this->config['endpoint']['depositStatus']);
            $body = [
                'asset'  => $code,
                'method' => $method
            ];
            $res = $this->request->make('POST', $body, $url);
            $this->errorFund($res);
            return $res->result;;
        }catch(Exception $e){
            throw new Exception($e->getMessage()); 
        }
    }

    public function withdrawInfo($code, $key, $amount){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            if(!isset($key) || !$key) throw new Exception('key param is mandatory.');
            $url = url_recode($this->config['endpoint']['withdrawInfo']);
            $body = [
                'asset'  => $code,
                'key'    => $key,
                'amount' => $amount
            ];
            $res = $this->request->make('POST', $body, $url);
            $this->errorFund($res);
            return $res->result;;
        }catch(Exception $e){
            throw new Exception($e->getMessage()); 
        }
    }
    
    public function withdrawFund($code, $key, $amount){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            if(!isset($amount) || !$amount) throw new Exception('amount param is mandatory.');
            if(!isset($key) || !$key) throw new Exception('key param is mandatory.');
            $url = url_recode($this->config['endpoint']['withdrawFund']);
            $body = [
                'asset'  => $code,
                'key'    => $key,
                'amount' => $amount
            ];
            $res = $this->request->make('POST', $body, $url);
            $this->errorFund($res);
            return $res->result;;
        }catch(Exception $e){
            throw new Exception($e->getMessage()); 
        }
    }

    public function cancelation($asset, $refid){
        try{
            if(!isset($asset) || !$asset) throw new Exception('asset param is mandatory.');
            if(!isset($refid) || !$refid) throw new Exception('refid param is mandatory.');
            $url = url_recode($this->config['endpoint']['withdrawCancel']);
            $body = [
                'asset'  => $asset,
                'refid'  => $refid
            ];
            $res = $this->request->make('POST', $body, $url);
            $this->errorFund($res);
            return $res->result;;
        }catch(Exception $e){
            throw new Exception($e->getMessage()); 
        }
    }
}
?>