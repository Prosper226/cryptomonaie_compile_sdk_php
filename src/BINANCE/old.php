<?php
    /**
     * @author BarkaLab
     * @developer Prosper SEDGO
     * @created_at 30/05/2022
     * @last_update 17/06/2022
     * @github https://github.com/Prosper226?tab=repositories
     * @version 1.0.
     */

    namespace Binance;

    use GuzzleHttp\Client;
    use Exception;

    class Binance_old {

        private $apiKey     = null;
        private $apiSecret  = null;
        private $baseUrl    = null; 
    
        /**
         * General API Information
         * - FRANCAIS
         * - - Tous les points de terminaison renvoient soit un objet JSON, soit un tableau.
         * - - Les données sont renvoyées dans l' ordre croissant . Le plus ancien en premier, le plus récent en dernier.
         * - - Tous les champs liés à l'heure et à l'horodatage sont en millisecondes .
         * - ENGLISH
         * - - All endpoints return either a JSON object or array.
         * - - Data is returned in ascending order. Oldest first, newest last.
         * - - All time and timestamp related fields are in milliseconds.
         * @source https://binance-docs.github.io/apidocs/spot/en/#introduction
         */
        public function __construct(){
            $this->apiKey       = "6gQg5Daxz9PStmsagARIYmEEYnKCtx5rYpyEIoTpQP8OY6BD3yCDZYkjn7NloEjK";
            $this->apiSecret    = "bvn7Wpaz7h822NqtaV4riYtUs1lLcfZte3bPoCPFAbQViC1TvVs3GRHh3VlHJ3ep";
            $this->baseUrl      = "https://api.binance.com";
        }


        public function mstime(){
        // public function check_server_time(){
            try{
                $body = [];
                $url = '/api/v3/time';
                $response    =  $this->BinanceRequest('GET', $url, $body, false);
                $response    = json_decode($response, true);
                return $response['serverTime'];
            }catch(Exception $e){
                throw new Exception ($e->getMessage());
            }
        }
        
        
        // private static function mstime(){
        //     $mstime = explode(' ',microtime());
        //     return $mstime[1].''.(int)($mstime[0]*1000);
        // }

        /**
         * Create HMAC-SHA256 
         * @param {*} query_string 
         * @return {HMAC | Exception} HMAC-SHA256 data
         */
        private function signature ($query_string) : String {
            try{
                return hash_hmac('sha256', $query_string, $this->apiSecret);
            }catch(Exception $e){
                throw new Exception ($e->getMessage());
            }
        }

        /**
         * @asynchronous
         * Make guzzle request to binance server
         * - http request to binance with axios
         * - requete http vers binance avec guzzle
         * @param {String} method request verbe | GET | POST 
         * @param {String} url binance request sub link
         * @param {Json} body json object data
         * @param {Booelean} HMAC true if request requires signature HMAC algorithm SHA256
         * @return {axios | Exception} HttpResponse object
         */
        private function BinanceRequest($method, $url, $body , $HMAC = true){
            try{
                $query_string = http_build_query($body);
                if($HMAC){
                    $signature = $this->signature($query_string);
                    $url .= "?$query_string&signature=$signature";
                }else{
                    $url .= "?$query_string";
                }
                $headers = [
                    'X-MBX-APIKEY'      => $this->apiKey,
                    'Content-Length'    => '0'
                ];
                // print_r(parse_url($url)); exit;
                $client = new Client([
                    'base_uri'  => $this->baseUrl,
                    // 'timeout'   => 2.0,
                    'headers'   => $headers,
                ]);
                $response = $client->request($method, $url);
                return $response->getBody()->getContents();
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        /**
         * @asynchronous
         * All Coins' Information (USER_DATA)
         * - FRANCAIS
         * - - Obtenez des informations sur les pièces (disponibles pour le dépôt et le retrait) pour l'utilisateur.
         * - ENGLISH
         * - - Get information of coins (available for deposit and withdraw) for user.
         * @return {Promise | Exception} information about all coin's on JSON format
         * @link https://binance-docs.github.io/apidocs/spot/en/#all-coins-39-information-user_data
         */
        public function all_coins_information() {
            try{
                $body = array(
                    "recvWindow"  =>   50000,
                    "timestamp"   =>    $this->mstime()
                );
                $url = '/sapi/v1/capital/config/getall';
                $response    =  $this->BinanceRequest('GET', $url, $body, true);
                return $response;
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }



        public function system_status(){
            try{
                $body = [];
                $url = '/sapi/v1/system/status';
                $response    =  $this->BinanceRequest('GET', $url, $body, false);
                return $response;
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        /**
         * @asynchronous
         * All networks list
         * @return {list}
         */
        public function networkList(){
            try{
                $body = array(
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = '/sapi/v1/capital/config/getall';
                $response    =   $this->BinanceRequest('GET', $url, $body, true);
                return $response;
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        /**
         * @asynchronous
         * Deposit Address (supporting network) (USER_DATA)
         * - FRANCAIS
         * - - Récupérer l'adresse de dépôt avec le réseau.
         * - - Si networkn'est pas envoyé, retour avec le réseau par défaut de la pièce.
         * - ENGLISH
         * - - Fetch deposit address with network.
         * - - If network is not send, return with default network of the coin
         * @param {String} coin required 
         * @param {String} network optional
         * @returns {Promise | Exception} retour address for coin on JSON format
         * @link https://binance-docs.github.io/apidocs/spot/en/#deposit-address-supporting-network-user_data
         */
        public function deposit_address($coin, $network = null){
            try{
                if(!$coin){
                    throw new Exception('param coin is missing');
                }else{
                    $body = array(
                        "coin"        =>    $coin,
                        "recvWindow"  =>    50000,
                        "timestamp"   =>    $this->mstime()
                    );
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
                    $url        = '/sapi/v1/capital/deposit/address';
                    $response   =  $this->BinanceRequest('GET', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        /**
         * @asynchronous
         * Deposit History(supporting network) (USER_DATA)
         * - FRANCAIS
         * - - Récupérer l'historique des dépôts.
         * - - le délai entre startTime et endTime doit être inférieur à 90 jours.
         * - - Valeur de status dans la réponse 
         * - - - 0 : en attente, 
         * - - - 6 : crédité mais impossible de retirer, 
         * - - - 1 : succès
         * - ENGLISH
         * - - Fetch deposit history.
         * - - time between startTime and endTime must be less than 90 days.
         * - - Value of status in response
         * - - - 0: pending,
         * - - - 6: credited but cannot withdraw, 
         * - - - 1: success
         * @param {Long} startTime required
         * @param {Long} endTime required
         * @return {Promise | Exception} Promise resolved
         * @link https://binance-docs.github.io/apidocs/spot/en/#deposit-history-supporting-network-user_data
         */
        public function deposit_history($startTime, $endTime){
            try{
                if(!$startTime || !$endTime){
                    throw new Exception('missing parameters');
                }else{
                    $body = array(
                        "startTime"   =>   $startTime, 
                        "endTime"     =>   $endTime,
                        "recvWindow"  =>   50000,
                        "timestamp"   =>   $this->mstime()
                    );
                    $url = '/sapi/v1/capital/deposit/hisrec';
                    $response    =   $this->BinanceRequest('GET', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        /**
         * @asynchronous
         * Withdraw (USER_DATA)
         * Enable Withdrawals
         * - FRANCAIS
         * - -  Pour utiliser cette fonction, le droit "Enable Withdrawals" 
         *      de votre API binance doit être activé : https://www.binance.com/en/my/settings/api-management.
         *      Par défaut sa zone est grisée, pour dégriser la zone, il faut ajouter une restriction d'adresse 
         *      IP en cliquant sur "Restrict access to trusted IPs only (Recommended)".
         *      Si le droit n'est pas activé, vous recevrez un code d'erreur -1002 de binance, 
         *      code indiquant que vous n'êtes pas autorisé à effectuer la requête.
         * - - Soumettre une demande de retrait.
         * - - Si network non envoyé, retour avec le réseau par défaut de la pièce.
         * - - {addressTag} correspond à un identifiant d'adresse secondaire pour les pièces telles que XRP, XMR, etc.
         * - - {withdrawOrderId} correspond à un identifiant client pour retrait
         * - ENGLISH
         * - -  To use this function, the "Enable Withdrawals" 
         *      right of your binance API must be activated: https://www.binance.com/en/my/settings/api-management.
         *      By default its zone is grayed out, to clear the zone, you need to add an 
         *      IP address restriction by clicking on "Restrict access to trusted IPs only (Recommended)".
         *      If the right is not activated, you will receive an error code -1002 from binance, 
         *      code indicating that you are not authorized to make the request.
         * - - Submit a withdraw request.
         * - - If network not send, return with default network of the coin.
         * - - {addressTag} as Secondary address identifier for coins like XRP,XMR etc.
         * - - {withdrawOrderId} as client id for withdraw
         * @param {String} coin              required
         * @param {String} address           required
         * @param {Number} amount            required
         * @param {String} network           optional
         * @param {String} addressTag        optional
         * @param {String} withdrawOrderId   optional
         * @return {Promise | Exception}    Promise resolved
         * @link https://binance-docs.github.io/apidocs/spot/en/#withdraw-user_data
         */
        public function withdraw($coin, $address, $amount, $network = null, $addressTag = null, $withdrawOrderId = null){
            try{
                $body = array(
                    "coin"        =>   $coin, 
                    "address"	  =>   $address,
                    "amount"      =>   $amount,
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                if  ($network)           $body['network']            =   $network;
                if  ($addressTag)        $body['addressTag']         =   $addressTag;
                if  ($withdrawOrderId)   $body['withdrawOrderId']    =   $withdrawOrderId;
                $url = '/sapi/v1/capital/withdraw/apply';
                $response    =   $this->BinanceRequest('POST', $url, $body, true);
                return $response;
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        /**
         * @asynchronous
         * Withdraw History (supporting network) (USER_DATA)
         * - FRANCAIS
         * - - Récupérer l'historique des retraits.
         * - - le délai entre startTime et endTime doit être inférieur à 90 jours.
         * - - Valeur de status dans la réponse 
         * - - - 0 : E-mail envoyé, 
         * - - - 1 : Annulé 
         * - - - 2 : En attente d'approbation 
         * - - - 3 : Refusé 
         * - - - 4 : Traitement 
         * - - - 5 : Échec 
         * - - - 6 : Terminé
         * - ENGLISH
         * - - Fetch withdraw history.
         * - - time between startTime and endTime must be less than 90 days.
         * - - Value of status in response
         * - - - 0 : Email Sent,
         * - - - 1 : Cancelled 
         * - - - 2 : Awaiting Approval 
         * - - - 3 : Rejected 
         * - - - 4 : Processing Finished
         * - - - 5 : Failure 
         * - - - 6 : Completed)
         * @param {Long} startTime required
         * @param {Long} endTime required
         * @return {Promise | Exception} Promise resolved
         * @link https://binance-docs.github.io/apidocs/spot/en/#withdraw-history-supporting-network-user_data
         */
        public function withdraw_history($startTime, $endTime){
            try{
                if(!$startTime || !$endTime){
                    throw new Exception('missing parameters');
                }else{
                    $body = array(
                        "startTime"   =>   $startTime, 
                        "endTime"     =>   $endTime,
                        "recvWindow"  =>   50000,
                        "timestamp"   =>   $this->mstime()
                    );
                    $url = '/sapi/v1/capital/withdraw/history';
                    $response    =   $this->BinanceRequest('GET', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        public function found_txid($txid, $startTime, $operation){
            try{
                $histories = [];
                switch(strtolower($operation)){
                    case 'deposit' :    
                        $histories = json_decode($this->deposit_history($startTime, $this->mstime()), true);
                        break;
                    case 'withdraw':   
                        $histories = json_decode($this->withdraw_history($startTime, $this->mstime()), true);
                        break;
                    default : throw new Exception("$operation not supported");
                }
                foreach($histories as $history){
                    if($history['txId'] == $txid){
                        return json_encode($history);
                    }
                }
                return json_encode(null);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }


        public function coin_information($coin){
            try{

                $informations = json_decode($this->all_coins_information(), true);
                foreach($informations as $information){
                    if($information['coin'] == strtoupper($coin)){
                        return json_encode($information);
                    }
                }
                return json_encode(null);

            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        
        //////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////
        
        public function GetAssetsThatCanBeConvertedIntoBNB(){
            try{
                
                $body = array(
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = '/sapi/v1/asset/dust-btc';
                $response    =   $this->BinanceRequest('POST', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        public function UserUniversalTransfer(){
                try{
                
                $body = array(
                    "type"        =>  "MAIN_UMFUTURE",
                    "asset"       =>  'TRXSHIB',
                    "amount"      =>  3,
                    "fromSymbol"  =>  'TRX',
                    "toSymbol"    =>  'SHIB',
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = '/sapi/v1/asset/transfer';
                $response    =   $this->BinanceRequest('POST', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function DustTransfer(){
                try{
                
                $body = array(
                    "asset"       =>  'TRX',
                    "asset"       =>  'SHIB',
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = '/sapi/v1/asset/dust';
                $response    =   $this->BinanceRequest('POST', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        public function DustLog(){
                try{
                
                $body = array(
                    "startTime"   =>  1654676306000,
                    "endTime"     =>   $this->mstime(),
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = 'sapi/v1/asset/dribblet';
                $response    =   $this->BinanceRequest('GET', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        public function AssetDividendRecord(){
                try{
                
                $body = array(
                    // "startTime"   =>  1654676306000,
                    // "endTime"     =>   $this->mstime(),
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = '/sapi/v1/asset/assetDividend';
                $response    =   $this->BinanceRequest('GET', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function QueryUserUniversalTransferHistory(){
                try{
                
                $body = array(
                    "type"        => 'MAIN_UMFUTURE',
                    "recvWindow"  =>   50000,
                    "timestamp"   =>   $this->mstime()
                );
                $url = '/sapi/v1/asset/transfer';
                $response    =   $this->BinanceRequest('GET', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        //////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////
        
        /**
         * 
         * Query Order (Check an order's status.) *
         * 
         * Cancel Order (TRADE) (Cancel an active order.)
         * 
         * New Order (TRADE) (Send in a new order.) *
         * 
         * Test New Order (TRADE) (Test new order creation and signature/recvWindow long. Creates and validates a new order but does not send it into the matching engine.) *
         * 
         * All Orders (Get all account orders; active, canceled, or filled.) *
         * 
         */
        
        public function test_new_order(){
            try{
                
                $body = array(
                    
                    "symbol"            =>  "TRXUSDT",
                    "side"              =>  "BUY",
                    "type"              =>  "MARKET",
            
                    "quantity"          =>  "150.000",
                    "newOrderRespType"  =>  "RESULT",
                    
                    "recvWindow"        =>   50000,
                    "timestamp"         =>   $this->mstime()
                );
                $url = '/api/v3/order/test';
                $response    =   $this->BinanceRequest('POST', $url, $body, true);
                return $response;
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        public function new_order($symbol = null, $side = null, $quantity = null){
            try{
                if(!$symbol || !$side || !$quantity){
                    throw new Exception('missing parameters');
                }else{
                    $body = array(
                        
                        "symbol"            =>  strtoupper($symbol),
                        "side"              =>  strtoupper($side),
                        "type"              =>  "MARKET",
            
                        "quantity"          =>  floatval($quantity),
                        "newOrderRespType"  =>  "RESULT",
                        
                        
                        "recvWindow"        =>  50000,
                        "timestamp"         =>  $this->mstime()
                    );
                    $url = '/api/v3/order';
                    $response    = $this->BinanceRequest('POST', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                // throw new Exception(explode('}',explode("\"msg\":",$e->getMessage())[1])[0]);
                throw new Exception($e->getMessage());
            }
        }
        
        public function query_order($symbol = null, $orderId = null){
            try{
                    if(!$symbol || !$orderId){
                    throw new Exception('missing parameters');
                }else{
                    $body = array(
                        
                        "symbol"            =>  strtoupper($symbol),
                        "orderId"           =>  $orderId,
                        "recvWindow"        =>  50000,
                        "timestamp"         =>  $this->mstime()
                    );
                    $url = '/api/v3/order';
                    $response    =   $this->BinanceRequest('GET', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        public function cancel_order($symbol = null, $orderId = null){
            try{
                if(!$symbol || !$orderId){
                    throw new Exception('missing parameters');
                }else{
                    $body = array(
                        
                        "symbol"            =>  strtoupper($symbol),
                        "orderId"           =>  $orderId,
                        "recvWindow"        =>   50000,
                        "timestamp"         =>   $this->mstime()
                    );
                    $url = '/api/v3/order';
                    $response    =   $this->BinanceRequest('DELETE', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        public function all_orders($symbol){
            try{
                if(!$symbol){
                    throw new Exception('missing parameters (symbol)');
                }else{
                    $body = array(
                        
                        "symbol"            =>  strtoupper($symbol),
                        "recvWindow"        =>   50000,
                        "timestamp"         =>   $this->mstime()
                    );
                    $url = '/api/v3/allOrders';
                    $response    =   $this->BinanceRequest('GET', $url, $body, true);
                    return $response;
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
    
        public function all_accounts_information(){
            try{
                $body = array(
                    "recvWindow"        =>   50000,
                    "timestamp"         =>   $this->mstime()
                );
                $url = '/api/v3/account';
                $response    =   $this->BinanceRequest('GET', $url, $body, true);
                return $response;
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function account_information($coin = null){
            try{
                if(!$coin){
                    throw new Exception('missing parameters (coin)');
                }else{
                    $informations = json_decode($this->all_accounts_information(), true);
                    foreach($informations['balances'] as $information){
                        if($information['asset'] == strtoupper($coin)){
                            return floatval($information['free']);
                        }
                    }
                    return json_encode(null);
                }
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function global_spot_balance(){
            try{
                
                $sum_btc    = 0.0;
                $sum_usdt   = 0.0;
                
                $balances   = json_decode($this->all_accounts_information(), true);
                $balances   = $balances['balances'];
                
                foreach($balances as $_balance){
                    $asset      = $_balance['asset'];
                    $quantity   = floatval($_balance['free']);
                    if($quantity){
                        try{
                            switch($asset){
                                case "BTC": 
                                    $btc_quantity   =   $quantity;
                                    $price          =   $this->get_symbol_ticker('BTCUSDT');
                                    $usdt_quantity  =   $quantity * $price;
                                    break;
                                case "USDT":
                                    $usdt_quantity  =   $quantity;
                                    $price          =   floatval(1 / $this->get_symbol_ticker('USDTBTC'));
                                    $btc_quantity   =   $quantity * $price;
                                    break;
                                default:
                                    $btc_price      =   $this->get_symbol_ticker($asset.'BTC');
                                    $usdt_price     =   $this->get_symbol_ticker($asset.'USDT');
                                    $btc_quantity   =   $quantity * $btc_price;
                                    $usdt_quantity  =   $quantity * $usdt_price;
                            }
                            
                            $sum_btc    += $btc_quantity;
                            $sum_usdt   += $usdt_quantity;
                        }catch(Exception $e){
                            error_log('Binance_log: '.$e->getMessage());
                            continue;
                        }
                    }
                }
                
                return json_encode(['btc' => $sum_btc, 'usdt' => $sum_usdt]);
                
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        
        private function get_symbol_ticker($symbol = null){
            try{
                if(!$symbol) throw new Exception('missing parameters (symbol)');
                $body = array(
                    "symbol"   =>   strtoupper($symbol)
                );
                // $url    = '/api/v3/avgPrice?symbol='.strtoupper($symbol);
                $url    = '/api/v3/avgPrice';
                $response   =   $this->BinanceRequest('GET', $url, $body, false);
                $response   = json_decode($response, true);
                return floatval($response['price']);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
        
        public function all_available_accounts_information(){
            try{
                $body = array(
                    "recvWindow"        =>   50000,
                    "timestamp"         =>   $this->mstime()
                );
                $url = '/api/v3/account';
                $response    =   $this->BinanceRequest('GET', $url, $body, true);
                
                $informations   = json_decode($response, true);
                $response = [];
                foreach($informations['balances'] as $information){
                    if(floatval($information['free'])){
                        $response[] = $information;
                    }
                }
                return json_encode($response);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
        
    }
?>