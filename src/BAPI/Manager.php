<?php

namespace Bapi;

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
        $this->app      =   new App('bapi');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Bapi');
    }

    public function balance($merchant = null){
        try{
            if($merchant && (! array_key_exists($merchant, $this->config['APP']['merchants']))) throw new Exception ("$merchant is not configured as payment method for $this->business");
            if($merchant){
                $mobile = $this->config['APP']['merchants'][$merchant]['mobile'];
                $url = url_recode($this->config['endpoint']['merchantBalance'], [$mobile]);
            }else{
                $url = url_recode($this->config['endpoint']['businessBalance']);
            } 
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function status($merchant = null){
        try{
            if($merchant && (! array_key_exists($merchant, $this->config['APP']['merchants']))) throw new Exception ("$merchant is not configured as payment method for $this->business");if($merchant && (! array_key_exists($merchant, $this->config['APP']['merchants']))) throw new Exception ("$merchant is not configured as payment method for $this->business");
            if($merchant){
                $mobile = $this->config['APP']['merchants'][$merchant]['mobile'];
                $url = url_recode($this->config['endpoint']['merchantStatus'], [$mobile]);
            }else{
                $url = url_recode($this->config['endpoint']['businessStatus']);
            }   
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deposit($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            if($merchant && (! array_key_exists($merchant, $this->config['APP']['merchants']))) throw new Exception ("$merchant is not configured as payment method for $this->business");
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != 3)) throw new Exception('Invalid input data');
            $body    =   [
                "phone"    =>   $data['phone'],
                "bash"     =>   $data['bash'],
                "amount"   =>   $data['amount'],
                "merchant" =>   $this->config['APP']['merchants'][$merchant]['mobile']
            ];
            $url         =   url_recode($this->config['endpoint']['deposit']);
            $res      =   $this->request->make('POST', $body, $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function withdraw($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            if($merchant && (! array_key_exists($merchant, $this->config['APP']['merchants']))) throw new Exception ("$merchant is not configured as payment method for $this->business");
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != 3)) throw new Exception('Invalid input data');
            $body    =   [
                "phone"    =>   $data['phone'],
                "bash"     =>   $data['bash'],
                "amount"   =>   $data['amount'],
                "merchant" =>   $this->config['APP']['merchants'][$merchant]['mobile']
            ];
            $url         =   url_recode($this->config['endpoint']['withdraw']);
            $res      =   $this->request->make('POST', $body, $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function cancel($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $url      =   url_recode($this->config['endpoint']['cancel'], [$bash]);
            $res      =   $this->request->make('GET', [], $url);
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

    public function smsHistory(){
        try{
            $url = url_recode($this->config['endpoint']['smsHistory']);
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function restitutes(Array $data = ["startTimestamp" => null, "endTimestamp" => null]){
        try{
            if( !isset($data['startTimestamp'], $data['endTimestamp']) ) throw new Exception('Invalid search input data');
            $url      =   url_recode($this->config['endpoint']['restitutes'], [ $data['startTimestamp'],$data['endTimestamp'] ]);
            $res      =   $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function zombiesHistory(Array $data = ["startTimestamp" => null, "endTimestamp" => null]){
        try{
            if( !isset($data['startTimestamp'], $data['endTimestamp']) ) throw new Exception('Invalid search input data');
            $url      =   url_recode($this->config['endpoint']['zombiesHistory'], [ $data['startTimestamp'],$data['endTimestamp'] ]);
            $res      =   $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
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

    public function payClub($merchant = null, $data = ["phone" => null, "amount" => null, "bash" => null]){
        try{
            if($merchant && (! array_key_exists($merchant, $this->config['APP']['merchants']))) throw new Exception ("$merchant is not configured as payment method for $this->business");
            if(! $this->config['APP']['merchants'][$merchant]['agent']) throw new Exception ("$merchant not support inter merchant payment for $this->business");
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != 3)) throw new Exception('Invalid input data');
            $body    =   [
                "phone"    =>   $data['phone'],
                "bash"     =>   $data['bash'],
                "amount"   =>   $data['amount'],
                "merchant" =>   $this->config['APP']['merchants'][$merchant]['mobile'],
            ];
            $url         =   url_recode($this->config['endpoint']['clubpayment']);
            $res      =   $this->request->make('POST', $body, $url);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }


}