<?php

namespace Coinbase;
use Exception;
use lab\App;
use lab\Request;

class Manager{

    private $app = null;
    private $config = null;
    private $business = null;
    private $request = null;
    
    public function __construct($business){
        $this->business =   $business;
        $this->app      =   new App('coinbase');
        $this->config   =   $this->app->getAccess($this->business);
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api']);
    }


    public function show_current_user(){
        try{
            $url = $this->config['endpoint']['current_user'];
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function list_accounts(){
        try{
            $url = $this->config['endpoint']['list_accounts'];
            $arrayobj = array();
            $pagination = true;
            do{
                $res = $this->request->make('GET', [], $url);
                if(isset($res->data)){
                    $arrayobj = array_merge($arrayobj, $res->data);
                }
                if (isset($res->pagination->next_uri) and !empty($res->pagination->next_uri)){
                    $url = $res->pagination->next_uri;
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return ($arrayobj);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function get_account_id($code = null){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $url = $this->config['endpoint']['list_accounts'];
            $pagination = true;
            do{
                $res = $this->request->make('GET', [], $url);
                if(isset($res->data)){
                    foreach($res->data as $account){
                        $account_code = $account->currency->code;
                        if($account_code == strtoupper($code)){
                            return $account->id;
                        }
                    }
                }
                if (isset($res->pagination->next_uri) and !empty($res->pagination->next_uri)){
                    $url = $res->pagination->next_uri;
                }else{
                    $pagination = false;
                }
            }while($pagination);
            throw new Exception('unknown code');
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function create_address($code){
        try{

            $id_account = $this->get_account_id(strtoupper($code));
            $body = json_encode(array ('name' => "New received $code address"));
            

            // $url = "/v2/accounts/$id_account/addresses";
            $url = $this->config['endpoint']['create_address'];

            $lien = '/v2/accounts/$id_account/addresses';



            return $url;
            // $res = $this->request->make('POST', $body, $url);

            // $timestamp = time();
            // $sign = $this->getSign($timestamp, $method, $request_path, $body);
            // $headers = array(
            //     'CB-ACCESS-KEY:'. $this->key,
            //     'CB-ACCESS-SIGN: '. $sign,
            //     'CB-ACCESS-TIMESTAMP: '. $timestamp,
            //     'CB-ACCESS-VERSION: 2021-10-05',
            //     'Content-Type: application/json'
            // );
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            // curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            // curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            // $result = curl_exec($ch);

            $address = json_decode($result);
            if(isset($address->data->destination_tag)){
                $tag = $address->data->destination_tag;
                return array('address' => $address->data->address, 'tag' => $tag);
            }
            return array('address' => $address->data->address);;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


    private function url_recode($str, $vars = []){
        try{
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

}

?>