<?php

namespace Crypto;

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
        $this->app      =   new App('crypto');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Crypto');
    }

    public function status(){
        try{
            $url = url_recode($this->config['endpoint']['status']);
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function cancel($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $url      =   url_recode($this->config['endpoint']['cancel'], [$bash]);
            $res      =   $this->request->make('POST', [], $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function check($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $url      =   url_recode($this->config['endpoint']['check'], [$bash]);
            $res      =   $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function callbackReceive(String $id){
        try{
            if( !isset($id) ) throw new Exception('Invalid id');
            $url    =   url_recode($this->config['endpoint']['callback'], [$id]);
            $res    =   $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function history(Array $data = ["type" => null, "startTimestamp" => null, "endTimestamp" => null]){
        try{
            if( !isset($data['type'], $data['startTimestamp'], $data['endTimestamp']) ) throw new Exception('Invalid search input data');
            $url      =   url_recode($this->config['endpoint']['history'], [ $data['type'], $data['startTimestamp'],$data['endTimestamp'] ]);
            $res      =   $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deposit($coin = null, $data = ["amount" => null, "bash" => null]){
        try{
            if($coin && (! array_key_exists($coin, $this->config['COINS']))) throw new Exception ("$coin is not configured as payment method for $this->business");
            if(!isset($data['amount'], $data['bash']) || (count($data) != 2)) throw new Exception('Invalid input data');
            $body    =   [
                "coin"     =>   $this->config['COINS'][$coin]['abbr'],
                "bash"     =>   $data['bash'],
                "amount"   =>   $data['amount']
            ];
            $url   =   url_recode($this->config['endpoint']['deposit']);
            $res   =   $this->request->make('POST', $body, $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function withdraw($coin = null, $data = ["address" => null, "amount" => null, "bash" => null]){
        try{
            if($coin && (! array_key_exists($coin, $this->config['COINS']))) throw new Exception ("$coin is not configured as payment method for $this->business");
            if(!isset($data['address'], $data['amount'], $data['bash']) || (count($data) != 3)) throw new Exception('Invalid input data');
            $body    =   [
                "address"   =>   $data['address'],
                "bash"      =>   $data['bash'],
                "amount"    =>   $data['amount'],
                "coin"      =>   $this->config['COINS'][$coin]['abbr']
            ];
            $url         =   url_recode($this->config['endpoint']['withdraw']);
            $res      =   $this->request->make('POST', $body, $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function balance($coin = null){
        try{
            if($coin && (! array_key_exists($coin, $this->config['COINS']))) throw new Exception ("$coin is not configured as payment method for $this->business");
            $url = url_recode($this->config['endpoint']['balance'], [$coin]);
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){ 
            throw new Exception($e->getMessage());
        }
    }


} 