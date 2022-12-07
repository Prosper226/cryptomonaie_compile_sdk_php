<?php

namespace lab;

use GuzzleHttp\Client;
use Exception;

class Request{

    private $apiAuth   = null;
    private $baseUrl  = null;

    public function __construct( $baseUrl, $apiAuth = []){
        $this->apiAuth  = $apiAuth;
        $this->baseUrl  = $baseUrl;
    }

    public function make($method = 'GET', $body = [], $endpoint = "", $headers = null, $decode = true){
        try{
            $timestamp  = time();
            $request_path = $endpoint;
            // $body = "";
            $sign       = $this->getSign($timestamp, $method, $request_path, $body);
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

            $response = $client->request($method, $endpoint, [
                // "json" => $body
            ]);
            return ($decode) ? json_decode($response->getBody()->getContents()) : $response->getBody()->getContents();

        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function getSign($timestamp, $method, $request_path, $body){
        $body = ($body) ? $body : "";
        $prehash = $timestamp.$method.$request_path.$body;
        return hash_hmac("sha256", $prehash, $this->apiAuth['apiSecret']);
    }


}



?>