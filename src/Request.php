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