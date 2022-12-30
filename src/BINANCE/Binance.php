<?php
namespace Binance;

use Exception;

class Binance{

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

    public function mstime(){
        try{
            $res = $this->manager->mstime();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function system_status(){
        try{
            $res = $this->manager->system_status();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function all_coins_information(){
        try{
            $res = $this->manager->all_coins_information();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function deposit_address($coin, $network = null){
        try{
            if(!isset($coin) || !$coin) throw new Exception('param coin is required.');
            $res = $this->manager->deposit_address($coin, $network);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function deposit_history($startTime = null, $endTime = null){
        try{
            if(!$startTime || !$endTime || $startTime > $endTime ){throw new Exception('missing or invalid parameters');}
            $res = $this->manager->deposit_history($startTime, $endTime);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function withdraw($coin, $address, $amount, $network = null, $addressTag = null, $withdrawOrderId = null){
        try{
            if(!isset($coin, $address, $amount) || !$coin || !$address || !$amount){throw new Exception('missing or invalid parameters');}
            $res = $this->manager->withdraw($coin, $address, $amount, $network, $addressTag, $withdrawOrderId);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function withdraw_history($startTime = null, $endTime = null){
        try{
            if(!$startTime || !$endTime || $startTime > $endTime){throw new Exception('missing or invalid parameters');}
            $res = $this->manager->withdraw_history($startTime, $endTime);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function new_order($symbol = null, $side = null, $quantity = null){
        try{
            if(!$symbol || !$side || !$quantity){throw new Exception('missing parameters');}
            $res = $this->manager->new_order($symbol, $side, $quantity);
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    private function all_accounts_information(){
        try{
            $res = $this->manager->all_accounts_information();
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    #########################
    #########################
    #########################
    public function found_txid($txid, $startTime, $operation){
        try{
            // $histories = [];
            // switch(strtolower($operation)){
            //     case 'deposit' :    
            //         $histories = json_decode($this->deposit_history($startTime, $this->mstime()), true);
            //         break;
            //     case 'withdraw':   
            //         $histories = json_decode($this->withdraw_history($startTime, $this->mstime()), true);
            //         break;
            //     default : throw new Exception("$operation not supported");
            // }
            // foreach($histories as $history){
            //     if($history['txId'] == $txid){
            //         return json_encode($history);
            //     }
            // }
            // return json_encode(null);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function coin_information($coin){
        try{

            // $informations = json_decode($this->all_coins_information(), true);
            // foreach($informations as $information){
            //     if($information['coin'] == strtoupper($coin)){
            //         return json_encode($information);
            //     }
            // }
            // return json_encode(null);

        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function account_information($coin = null){
        try{
            if(!$coin){
                throw new Exception('missing parameters (coin)');
            }else{
                $all_accounts_information = $this->manager->all_accounts_information();
                $informations = json_decode($all_accounts_information, true);
                foreach($informations['balances'] as $information){
                    if($information['asset'] == strtoupper($coin)){
                        // return floatval($information['free']);
                        return json_encode(["code" => 200, "data" => floatval($information['free'])]);
                    }
                }
                // return json_encode(null);
                return ["code" => 200, "data" => null];
            }
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function global_spot_balance(){
        try{
            
            // $sum_btc    = 0.0;
            // $sum_usdt   = 0.0;
            
            // $balances   = json_decode($this->all_accounts_information(), true);
            // $balances   = $balances['balances'];
            
            // foreach($balances as $_balance){
            //     $asset      = $_balance['asset'];
            //     $quantity   = floatval($_balance['free']);
            //     if($quantity){
            //         try{
            //             switch($asset){
            //                 case "BTC": 
            //                     $btc_quantity   =   $quantity;
            //                     $price          =   $this->get_symbol_ticker('BTCUSDT');
            //                     $usdt_quantity  =   $quantity * $price;
            //                     break;
            //                 case "USDT":
            //                     $usdt_quantity  =   $quantity;
            //                     $price          =   floatval(1 / $this->get_symbol_ticker('USDTBTC'));
            //                     $btc_quantity   =   $quantity * $price;
            //                     break;
            //                 default:
            //                     $btc_price      =   $this->get_symbol_ticker($asset.'BTC');
            //                     $usdt_price     =   $this->get_symbol_ticker($asset.'USDT');
            //                     $btc_quantity   =   $quantity * $btc_price;
            //                     $usdt_quantity  =   $quantity * $usdt_price;
            //             }
                        
            //             $sum_btc    += $btc_quantity;
            //             $sum_usdt   += $usdt_quantity;
            //         }catch(Exception $e){
            //             error_log('Binance_log: '.$e->getMessage());
            //             continue;
            //         }
            //     }
            // }
            
            // return json_encode(['btc' => $sum_btc, 'usdt' => $sum_usdt]);
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function all_available_accounts_information(){
        try{
            // $body = array(
            //     "recvWindow"        =>   50000,
            //     "timestamp"         =>   $this->mstime()
            // );
            // $url = '/api/v3/account';
            // $response    =   $this->BinanceRequest('GET', $url, $body, true);
            
            // $informations   = json_decode($response, true);
            // $response = [];
            // foreach($informations['balances'] as $information){
            //     if(floatval($information['free'])){
            //         $response[] = $information;
            //     }
            // }
            // return json_encode($response);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}




?>