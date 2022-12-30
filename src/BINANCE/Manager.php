<?php

namespace Binance;

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
        $this->app      =   new App('binance');
        $this->config   =   $this->app->getAccess($this->business);
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Binance');
    }

    public function mstime(){
        try{
            $url = url_recode($this->config['endpoint']['serverTime']);
            $response    =  $this->request->make('GET', [], $url, null, false);
            $response    = json_decode($response, true);
            return $response['serverTime'];
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    public function get_symbol_ticker($symbol = null){
        try{
            if(!$symbol) throw new Exception('missing parameters (symbol)');
            $body       = array("symbol" => strtoupper($symbol));
            $url        = url_recode($this->config['endpoint']['get_symbol_ticker']);
            $response   = $this->request->make('GET', $body, $url, null, false);
            $response   = json_decode($response, true);
            if(!isset($response['price'])){throw new Exception($response['msg']);}
            return floatval($response['price']);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function all_coins_information() {
        try{
            $body = array("timestamp"   =>  $this->mstime());
            $url = url_recode($this->config['endpoint']['all_coins_information']);
            $response    =  $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function system_status(){
        try{
            $url = url_recode($this->config['endpoint']['system_status']);
            $response    =  $this->request->make('GET', [], $url, null, false);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deposit_address($coin, $network = null){
        try{
            if(!isset($coin) || !$coin) throw new Exception('param coin is required.');
            $body = array( "coin" => $coin, "timestamp"   => $this->mstime() );
            if($network){
                $final_network = null;
                switch(strtoupper($network)){
                    case 'BEP2'     :   $final_network   =   'BNB';break;
                    case 'BEP20'    :   $final_network   =   'BSC';break;
                    case 'TRC20'    :   $final_network   =   'TRX';break;
                    case 'ERC20'    :   $final_network   =   'ETH';break;
                    default         :   $final_network   =   $network;
                }
                $body['network'] = $final_network;
            }
            $url  = url_recode($this->config['endpoint']['deposit_address']);
            $response   =  $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deposit_history($startTime = null, $endTime = null){
        try{
            if(!$startTime || !$endTime || $startTime > $endTime){throw new Exception('missing or invalid parameters');}
            $body = array(
                "startTime"   =>   $startTime, 
                "endTime"     =>   $endTime,
                "timestamp"   =>   $this->mstime()
            );
            $url  = url_recode($this->config['endpoint']['deposit_history']);
            $response    =   $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function withdraw($coin, $address, $amount, $network = null, $addressTag = null, $withdrawOrderId = null){
        try{
            if(!isset($coin, $address, $amount) || !$coin || !$address || !$amount){throw new Exception('missing or invalid parameters');}
            $body = array(
                "coin"        =>   $coin, 
                "address"	  =>   $address,
                "amount"      =>   $amount,
                "timestamp"   =>   $this->mstime()
            );
            if  ($network)           $body['network']            =   $network;
            if  ($addressTag)        $body['addressTag']         =   $addressTag;
            if  ($withdrawOrderId)   $body['withdrawOrderId']    =   $withdrawOrderId;
            $url = url_recode($this->config['endpoint']['withdraw']);
            $response    =    $this->request->make('POST', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function withdraw_history($startTime, $endTime){
        try{
            if(!$startTime || !$endTime || $startTime > $endTime){throw new Exception('missing or invalid parameters');}
            $body = array(
                "startTime"   =>   $startTime, 
                "endTime"     =>   $endTime,
                "timestamp"   =>   $this->mstime()
            );
            $url = url_recode($this->config['endpoint']['withdraw_history']);
            $response    =   $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function new_order($symbol = null, $side = null, $quantity = null){
        try{
            if(!$symbol || !$side || !$quantity){throw new Exception('missing parameters');}
            $body = array(
                "symbol"            =>  strtoupper($symbol),
                "side"              =>  strtoupper($side),
                "type"              =>  "MARKET",
                "quantity"          =>  floatval($quantity),
                "newOrderRespType"  =>  "RESULT",
                "timestamp"         =>  $this->mstime()
            );
            $url = url_recode($this->config['endpoint']['order']);
            $response    = $this->request->make('POST', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function query_order($symbol = null, $orderId = null){
        try{
            if(!$symbol || !$orderId){throw new Exception('missing parameters');}
            $body = array(
                "symbol"            =>  strtoupper($symbol),
                "orderId"           =>  $orderId,
                "timestamp"         =>  $this->mstime()
            );
            $url = url_recode($this->config['endpoint']['order']);
            $response    = $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function all_orders($symbol = null){
        try{
            if(!$symbol){throw new Exception('missing parameters (symbol)');}
            $body = array(
                "symbol"            =>  strtoupper($symbol),
                "timestamp"         =>   $this->mstime()
            );
            $url = url_recode($this->config['endpoint']['all_orders']);
            $response    = $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function all_accounts_information(){
        try{
            $body = array( "timestamp"  =>   $this->mstime() );
            $url = url_recode($this->config['endpoint']['account']);
            $response    = $this->request->make('GET', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    
}

?>