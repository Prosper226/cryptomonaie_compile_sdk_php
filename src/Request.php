<?php

namespace lab;

use GuzzleHttp\Client;
use Exception;

class Request{

    private $apiAuth = null;
    private $baseUrl = null;
    private $prefix  = null;

    public function __construct( $baseUrl, $apiAuth = [], $prefix = null){
        $this->apiAuth  = $apiAuth;
        $this->baseUrl  = $baseUrl;
        $this->prefix   = $prefix;
    }

    public function make($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $maker = 'make'.$this->prefix;
            return $this->$maker($method, $body, $endpoint, $headers, $decode);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc COINBASE ------- */
    private function makeCoinbase($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $timestamp  = time();
            $request_path = $endpoint;
            $sign       = $this->signCoinbase($timestamp, $method, $request_path, $body);
            $headers    = [
                'CB-ACCESS-KEY'         =>  $this->apiAuth['apiKey'],
                'CB-ACCESS-SIGN'        =>  $sign,
                'CB-ACCESS-TIMESTAMP'   =>  $timestamp,
                'CB-ACCESS-VERSION'     =>  '2021-10-05',
                'Content-Type'          =>  'application/json',
            ];
            $client = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers
            ]);
            $body = ($body) ? ["json" => $body] : [];
            $response = $client->request($method, $endpoint, $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private function signCoinbase($timestamp, $method, $request_path, $body){
        $body = ($body) ? json_encode($body) : "";
        $prehash = $timestamp.$method.$request_path.$body;
        return hash_hmac("sha256", $prehash, $this->apiAuth['apiSecret']);
    }
    /* ------   fin Bloc COINBASE ------- */
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc KRAKEN ------- */
    private function makeKraken($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{

            if(!isset($body['nonce'])) {
                $nonce = explode(' ', microtime());
                $body['nonce'] = $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');
            }

            $sign           = $this->signKraken($endpoint, $body);
            $headers        = [
                'API-Key'         =>  $this->apiAuth['apiKey'],
                'API-Sign'        =>  $sign,
                'Content-Type'    =>  'application/x-www-form-urlencoded'
            ];

            $client     = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers
            ]);
            
            $body = ($body) ? ["form_params" => $body] : [];
            // print_r($this->apiAuth['privateKey']);
            $response = $client->request($method, $endpoint, $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private function signKraken($path, $request){
        try{
            $postdata = http_build_query($request, '', '&');
            $sign = hash_hmac('sha512', $path . hash('sha256', $request['nonce'] . $postdata, true), base64_decode($this->apiAuth['privateKey']), true);
            return base64_encode($sign);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc KRAKEN ------- */
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc BINANCE ------- */
    private function makeBinance($method = 'GET', $body = [], $endpoint = "", $headers = null, $HMAC = true){
        try{
            if($HMAC){
                $body['recvWindow'] =  50000;
                $query_string = http_build_query($body);
                $signature = $this->signBinance($query_string);
                $endpoint .= "?$query_string&signature=$signature";
            }else{
                $query_string = http_build_query($body);
                $endpoint .= "?$query_string";
            }
            $headers = [
                'X-MBX-APIKEY'      => $this->apiAuth['apiKey'],
                'Content-Length'    => '0'
            ];
            $client = new Client([
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers,
            ]);
            $response = $client->request($method, $endpoint);
            return $response->getBody()->getContents();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private function signBinance($query_string){
        try{
            return hash_hmac('sha256', $query_string, $this->apiAuth['apiSecret']);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc BINANCE ------- */
     //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc NOWPAYMENT ------- */
    private function makeNowpayment($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            // return $this->tokenNowpayment();
            $headers = [
                'x-api-key'     =>  $this->apiAuth['apiKey'],
                'Content-Type'  =>  'application/json',
                'Authorization' =>  'Bearer '.$this->tokenNowpayment()
            ];
            $client     = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
            ]);
            // $body = ($body) ? json_encode($body): '';
            if(isset($body['withdrawals']) && $body['withdrawals']){
                // $body = [
                //     "withdrawals" => json_encode($body['withdrawals'])
                // ];
                // $body = $body;
                $address    = $body['withdrawals']['address'];
                $currency   = $body['withdrawals']['currency'];
                $amount     = $body['withdrawals']['amount'];
                // $body = 
                //     "{
                //         withdrawals : [
                //             {
                //                 'address': $address,
                //                 'currency': $currency,
                //                 'amount': $amount,
                //             }
                //         ]
                //     }
                // ";

                $body = 
                "{'withdrawals' : [".json_encode($body['withdrawals'])."]}";

            }else{
                $body = json_encode($body);
            }
            // // $client  = new Client();
            // // $request = new \GuzzleHttp\Psr7\Request('GET', $this->baseUrl.$endpoint, $headers, $body);
            $request    = new \GuzzleHttp\Psr7\Request($method, $endpoint, $headers, $body);
            $response   = $client->sendAsync($request)->wait();
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
            // return $body;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private function tokenNowpayment(){
        try{
            $client     = new Client();
            $body       = json_encode(["email" => $this->apiAuth['apiEmail'], "password" => $this->apiAuth['apiPass']]);
            $request    = new \GuzzleHttp\Psr7\Request($this->apiAuth['auth']['method'], $this->apiAuth['auth']['link'], ['Content-Type'  =>  'application/json'], $body);
            $response   = $client->sendAsync($request)->wait();
            $response   = json_decode($response->getBody()->getContents());
            return $response->token;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc NOWPAYMENT ------- */
     //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc MOOVAFRICA ------- */
    private function makeMoovafrica($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $credentials = base64_encode($this->apiAuth['username'].':'.$this->apiAuth['password']);
            $headers    = [
                'Content-Type'      =>  'application/json',
                'Authorization'     => 'Basic ' . $credentials,
                'command-id'        =>  $endpoint,
            ];
            $client = new Client([
                'verify'    => false,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers
            ]);
            $body = ($body) ? ["json" => $body] : [];
            $response = $client->request($method, '', $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
            // return $body;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc MOOVAFRICA ------- */
     //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc LIGDICASH ------- */
    private function makeLigdicash($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $headers    = [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiAuth['apiToken'],
                'Apikey'        =>  $this->apiAuth['apiKey'],
            ];
            $client = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers
            ]);
            // return $headers;
            $body = ($body) ? ["json" => $body] : [];
            $response = $client->request($method, $endpoint, $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
            // print_r(json_encode($body));
            // return $endpoint;



            
            // $client     = new Client([
            //     'verify'    => true,
            //     'base_uri'  => $this->baseUrl,
            // ]);
            // // $body = ($body) ? json_encode($body): '';
            // if(isset($body['withdrawals']) && $body['withdrawals']){
            //     // $body = [
            //     //     "withdrawals" => json_encode($body['withdrawals'])
            //     // ];
            //     // $body = $body;
            //     $address    = $body['withdrawals']['address'];
            //     $currency   = $body['withdrawals']['currency'];
            //     $amount     = $body['withdrawals']['amount'];
            //     // $body = 
            //     //     "{
            //     //         withdrawals : [
            //     //             {
            //     //                 'address': $address,
            //     //                 'currency': $currency,
            //     //                 'amount': $amount,
            //     //             }
            //     //         ]
            //     //     }
            //     // ";

            //     $body = 
            //     "{'withdrawals' : [".json_encode($body['withdrawals'])."]}";

            // }else{
            //     $body = json_encode($body);
            // }
            // // // $client  = new Client();
            // // // $request = new \GuzzleHttp\Psr7\Request('GET', $this->baseUrl.$endpoint, $headers, $body);
            // $request    = new \GuzzleHttp\Psr7\Request($method, $endpoint, $headers, $body);
            // $response   = $client->sendAsync($request)->wait();
            // return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
            // return $body;

        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc LIGDICASH ------- */
     //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc BAPI BURKINA------- */
    private function makeBapi($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            ( isset($body['merchant']) && $body['merchant'] ) ?? $this->apiAuth['merchant'] = $body['merchant'];
            $headers = [
                'Content-Type'  => "application/json",
                'BAPI-AUTH-KEY' => "Bearer ".$this::signBapi($this->apiAuth)
            ];
            $authentication = ['haemasu', 'toolbelt'];
            $client = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers,
                'auth'      => $authentication,
            ]);
            $body = ($body) ? ["json" => $body] : [];
            $response = $client->request($method, $endpoint, $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private static function signBapi($textToEncrypt = []){
        try{
            $textToEncrypt    = json_encode($textToEncrypt);
            $HASH_SECRET      = '0f678fcbea273a6be2307fba78ab2a88';
            $HASH_ALGORITHM   = 'AES-256-CBC';
            $IV               = substr($HASH_SECRET, 0, 16);
            $encryptedMessage = openssl_encrypt($textToEncrypt, $HASH_ALGORITHM, $HASH_SECRET,0,$IV);
            return $encryptedMessage;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc BAPI BURKINA ------- */
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc CRYPTO SYSTEM------- */
    private function makeCrypto($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $headers = [
                'Content-Type'       => "application/json",
                'CRYPTOSYS-AUTH-KEY' => "Bearer ".$this::signCrypto($this->apiAuth)
            ];
            // $authentication = ['intellectual', 'property'];
            $client = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers,
                // 'auth'      => $authentication,
            ]);
            // return $headers;
            $body = ($body) ? ["json" => $body] : [];
            $response = $client->request($method, $endpoint, $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private static function signCrypto($textToEncrypt = []){
        try{
            $textToEncrypt    = json_encode($textToEncrypt);
            $HASH_SECRET      = '0f678fcbea273a6be2307fba78ab2a88';
            $HASH_ALGORITHM   = 'AES-256-CBC';
            $IV               = substr($HASH_SECRET, 0, 16);
            $encryptedMessage = openssl_encrypt($textToEncrypt, $HASH_ALGORITHM, $HASH_SECRET,0,$IV);
            return $encryptedMessage;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc CRYPTO SYSTEM ------- */
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc PAYDUNYA------- */
    private function makePaydunya($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $headers = [
                'Content-Type'          => "application/json",
                'PAYDUNYA-MASTER-KEY'   => $this->apiAuth['masterKey'],
                'PAYDUNYA-PRIVATE-KEY'  => $this->apiAuth['privateKey'],
                'PAYDUNYA-TOKEN'        => $this->apiAuth['token']
            ];
            $client = new Client([
                'verify'    => true,
                'base_uri'  => $this->baseUrl,
                'headers'   => $headers,
                // 'auth'      => $authentication,
            ]);
            $body = ($body) ? ["json" => $body] : [];
            $response = $client->request($method, $endpoint, $body);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /* ------   fin Bloc PAYDUNYA ------- */
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /* ------   debut Bloc INTOUCH------- */
    private function makeIntouch($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $body['partner_id']     = $this->apiAuth['partner_id'];
            $body['login_api']      = $this->apiAuth['login_api'];
            $body['password_api']   = $this->apiAuth['password_api'];
            $endpoint = $this->baseUrl.$endpoint;
            $response = $this->curlIntouch($endpoint, json_encode($body), $method);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    private function curlIntouch($endpoint, $payLoads, $method){
        try{
            // Initialisation
            $curl_handle=curl_init();
            curl_setopt($curl_handle,CURLOPT_URL,$endpoint);
            curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
            curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
            
            
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $method);
            
            // Setting headers
            curl_setopt($curl_handle,CURLOPT_HEADER,1);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            
            // Digest authentifiaction specifying if the method is "PUT". Otherwise, authentification remains basic
            if($method == "PUT") curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);

            curl_setopt($curl_handle, CURLOPT_USERPWD, $this->apiAuth['username'] . ":" . $this->apiAuth['password']);
            
            // Setting Payloads
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payLoads);
            
            // error_log(print_r(["curlClient" => [json_decode($payLoads, true), $endpoint, $method]], true));
            
            // Running curl
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            
            // error_log("Buffer is: ".json_encode($buffer));
            // Catching response
            if (empty($buffer)){
                
                // Notify in error_log when nothing returns from URL
                // error_log("Nothing returned from url.");
            }
            else {
                $resArray = explode("{",$buffer);
                if(count($resArray) < 2) {
                    // error_log($buffer);
                    throw new Exception ('failure from intouch server.');
                }
                // Return response as object
                $res = json_decode("{".$resArray[1],true);
                return $res;
            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }
    /* ------   fin Bloc INTOUCH ------- */

}


function url_recode($str, $vars = []){
    try{
        if(!isset($str) || !$str) throw new Exception('url non fournit.');
        $array = explode('/', $str);
        $j = -1;
        foreach($array as $value){
            if (strpos($value, ':') !== false) {
                $str = str_replace($value, $vars[++$j], $str);
            }
        }
        return $str;
    }catch(Exception $e){
        throw new Exception($e->getMessage());
    }
}


// function getPrefixNames(){
//     try{
//         $FILE   = file_get_contents(dirname(__DIR__)."/.config.json");
//         $LIBS   = json_decode( $FILE , true);
//         $PREFIX = [];
//         foreach($LIBS as $key => $value){
//             $PREFIX[] = $key;
//         }
//         return $PREFIX;
//     }catch(Exception $e){
//         throw new Exception($e->getMessage());
//     }
// }


?>