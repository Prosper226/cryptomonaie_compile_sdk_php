<?php

namespace Coinbase;

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
        $this->app      =   new App('coinbase');
        $this->config   =   $this->app->getAccess($this->business);
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Coinbase');
    }

    /**
     * PRIVATE GROUPS
     */
    private function get_account_id($code = null){
        try{
            if(!isset($code) || !$code) throw new Exception('code param is mandatory.');
            $url = url_recode($this->config['endpoint']['list_accounts']);
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
                    $url = url_recode($res->pagination->next_uri);
                }else{
                    $pagination = false;
                }
            }while($pagination);
            throw new Exception('unknown code');
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function preg_address($code ,$address){
        try{
            $code = strtoupper($code);
            switch($code){
                case 'FX' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'AVAX' : $regex = "^0x[a-fA-F0-9]{40}$";   break;
                case 'JASMY' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'WCFG' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'TRU' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'WLUNA' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'RLY' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'SHIB' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'RLC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'SKL' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'WBTC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'XRP' : $regex = "^r[1-9a-km-zA-HJ-NP-Z]{25,35}$";   break;
                case 'ZEC' : $regex = "^(t1|t3)[a-km-zA-HJ-NP-Z1-9]{33}$";   break;
                case 'RGT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'UNI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'SOL' : $regex = "[1-9A-HJ-NP-Za-km-z]{32,44}";   break;
                case 'DNT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'YFI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'REPV2' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'XYO' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'RAI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'GRT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'SNX' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'REN' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'TRB' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'QUICK' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'QNT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'UMA' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'USDC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'REQ' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'SUSHI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'NU' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'RAD' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'UST' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'TRIBE' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'USDT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'OGN' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'XLM' : $regex = "^G[A-Z2-7]{55}$";   break;
                case 'OMG' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'NKN' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'STORJ' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'PLA' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'MATIC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'NMR' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'MASK' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'OXT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'MIR' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'PAX' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'DOT' : $regex = "^[1-9A-HJ-NP-Za-km-z]{47,48}$";   break;
                case 'POLY' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ORN' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'LTC' : $regex = "^((L|M)[a-km-zA-HJ-NP-Z1-9]{25,34})|^(ltc1([qpzry9x8gf2tvdw0s3jn54khce6mua7l]{39}|[qpzry9x8gf2tvdw0s3jn54khce6mua7l]{59}))$";   break;
                case 'EOS' : $regex = "(^[a-z1-5.]{1,11}[a-z1-5]$)|(^[a-z1-5.]{12}[a-j1-5]$)";   break;
                case 'ENJ' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'LPT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'MKR' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'KNC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'MLN' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'YFII' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'IOTX' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'LRC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'CRV' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'KEEP' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ICP' : $regex = "^[0-9a-f]{64}$";   break;
                case 'FARM' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'FET' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ZEN' : $regex = "^(zn|zr|zt|zs)[a-km-zA-HJ-NP-Z1-9]{25,34}$";   break;
                case 'GTC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'FIL' : $regex = "^[ft][1][a-z2-7]{39}$";   break;
                case 'MANA' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'CVC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'DDX' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'CLV' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'CHZ' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'COMP' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'DOGE' : $regex = "^((D|A|9)[a-km-zA-HJ-NP-Z1-9]{25,34})$";   break;
                case 'DAI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'DASH' : $regex = "^([X7][a-km-zA-HJ-NP-Z1-9]{25,34})$";   break;
                case 'COTI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'BAT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'CGLD' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'BOND' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'BAND' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'BNT' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'FORTH' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ADA' : $regex = "(^(addr)1[ac-hj-np-z02-9]{6,}$)|(^(DdzFFz|Ae2td)[1-9A-HJ-NP-Za-km-z]+)";   break;
                case 'LINK' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'CTSI' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'BTRST' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ACH' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'AAVE' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'AGLD' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ALGO' : $regex = "^[A-Z2-7]{58}$";   break;
                case 'BAL' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'AXS' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case '1INCH' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'AMP' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'REP' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ANKR' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ZRX' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ATOM' : $regex = "^cosmos1[ac-hj-np-z02-9]{38}$";   break;
                case 'XTZ' : $regex = "(tz[1|2|3]([a-zA-Z0-9]){33})|(^KT1([a-zA-Z0-9]){33}$)";   break;
                case 'ETC' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'ETH' : $regex = "^(?:0x)?[0-9a-fA-F]{40}$";   break;
                case 'BCH' : $regex = "^([13][a-km-zA-HJ-NP-Z1-9]{25,34})|^((bitcoincash:)?(q|p)[a-z0-9]{41})|^((BITCOINCASH:)?(Q|P)[A-Z0-9]{41})$";   break;
                case 'BTC' : $regex = "^([13][a-km-zA-HJ-NP-Z1-9]{25,34})|^(bc1([qpzry9x8gf2tvdw0s3jn54khce6mua7l]{39}|[qpzry9x8gf2tvdw0s3jn54khce6mua7l]{59}))$";   break;
                default : return false;
            }
            return (preg_match("/$regex/", $address)) ? true : false;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function list_addresses($code){
        try{
            $id_account = $this->get_account_id($code);
            $url        = url_recode($this->config['endpoint']['list_addresses'], [$id_account]);
            $arrayobj   = array();
            $pagination = true;
            do{
                $result     = $this->request->make('GET', [], $url);
                $arrayobj   = array_merge($arrayobj, $result->data);
                if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                    $url    = url_recode($result->pagination->next_uri);
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return $arrayobj;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function get_address_id($address, $code){

        // $code = strtoupper($code);
        // $addresses = $this->list_addresses($code);
        // if(count($addresses)){
        //     foreach($addresses as $self){
        //         $self_address = $self->address;
        //         if($self_address == $address){
        //             return $self->id;
        //         }
        //     }
        // }
        // return null;

        try{
            $id_account = $this->get_account_id($code);
            $url        = url_recode($this->config['endpoint']['list_addresses'], [$id_account]);
            $pagination = true;
            do{
                $res = $this->request->make('GET', [], $url);
                if(isset($res->data)){
                    foreach($res->data as $self){
                        $self_address = $self->address;
                        if($self_address == $address){
                            return $self->id;
                        }
                    }
                }
                if (isset($res->pagination->next_uri) and !empty($res->pagination->next_uri)){
                    $url = url_recode($res->pagination->next_uri);
                }else{
                    $pagination = false;
                }
            }while($pagination);
            throw new Exception('unknown code');
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    
    }

    private function blockchain_confirmations($tx_hash){
        $bcinfo = json_decode(file_get_contents("https://blockchain.info/rawtx/".$tx_hash), true);
        $latest = json_decode(file_get_contents("https://blockchain.info/latestblock"), true);
        if(isset($bcinfo['block_height'])) {
            $block_height =  $bcinfo['block_height'];
            $height       =  $latest['height'];
            $confirmations = json_decode($this->response($height - $block_height + 1))->Confirmations;
            return $confirmations;
            
        }else{
            return 0; // not yet has any confirmations
        }
    }

    private function response ($result) {   
        header("Content-Type:application/json");
        $response['Confirmations'] = $result;
        $json_response = json_encode($response);
        return $json_response;
    }

    private function show_an_account($code){
        try{
            $id_account = $this->get_account_id($code);
            $url     = url_recode($this->config['endpoint']['show_an_account'], [$id_account]);
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function get_account_balance($code){
        try{
            $account = $this->show_an_account($code);
            return $account->data->balance->amount;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    private function check_sending_transaction($code = null, $id_transaction = null){
        try{
            $code = strtoupper($code);
            $id_account = $this->get_account_id($code);    
            $url     = url_recode($this->config['endpoint']['check_sending_transaction'], [$id_account, $id_transaction]);
            $result  = $this->request->make('GET', [], $url);
            $status  = $result->data->status;
            $status_bin = ($status == 'completed') ? 1 : 0; 
            $confirmations = $result->data->network->confirmations;
            return array('tx_id' => $id_transaction, 'statut' => $status_bin, 'confirmations' => $confirmations);
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    /**
     * PUBLIC GROUPS
     */
    public function create_address($code){
        try{
            $id_account = $this->get_account_id($code);
            $body    = array ('name' => "New received $code address");
            $url     = url_recode($this->config['endpoint']['create_address'], [$id_account]);
            $result = $this->request->make('POST', $body, $url);
            switch($code){
                case 'XLM':
                case 'XRP' :    
                    $addressReturn = explode(':::ucl:::', $result->data->address);
                    return (object)['address' => $addressReturn[0], 'tag' => $addressReturn[1], 'network'=> $result->data->network];
                default : 
                    if(isset(($result->data->destination_tag))){ // case avalable for ATOM crypto
                        $tag = $result->data->destination_tag;
                        return (object)['address' => $result->data->address, 'tag' => $tag, 'network' => $result->data->network];
                    }
                    return (object)['address' => $result->data->address, 'network' => $result->data->network];
            }
    
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function check_transaction($code, $address, $amount){
        try{
            if(!$this->preg_address($code, $address)){ throw new Exception('invalid address');}
            if($amount <= 0){throw new Exception('invalid amount');}
            
            $cryto_not_need_ids_to_check_transaction = array('BTC');
            if (!in_array($code, $cryto_not_need_ids_to_check_transaction)){
                $address = $this->get_address_id($address, $code);
            }

            $id_account = $this->get_account_id(strtoupper($code));
            $url        = url_recode($this->config['endpoint']['check_transaction'], [$id_account, $address]);
            $result     = $this->request->make('GET', [], $url);
            $payment = 0;
            if(isset($result->errors[0]->id)){
                throw new Exception('request path not found.');
            }else{
                if(isset($result->data[0])){
                    $data = $result->data[0];
                    $payment = !$payment;
                    $amount_received = $data->amount->amount;
                    $status = $data->status;
                    $payment_weight = ($amount_received == $amount)?0:(($amount_received < $amount)?1:-1);
                    $tx_id = $data->id;
                    $tx_hash = $data->network->hash;
                    $confirmations = $this->blockchain_confirmations($tx_hash);
                }
                return (Object)($payment)
                        ?(($status != 'completed')
                            ?array('payment' => $payment, 'tx_id' => $tx_id, 'amount_received' => $amount_received, 'statut' => 0, 'payment_weight' => $payment_weight, 'confirmations' => $confirmations)
                            :array('payment' => $payment, 'tx_id' => $tx_id, 'amount_received' => $amount_received, 'statut' => 1, 'payment_weight' => $payment_weight))
                        :array('payment' => $payment);
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function send_monney($code, $recepteur, $amount, $memo, $idem = null){
        try{
            $idem = (!$idem) ? $idem : time();
            $current_account_balance = $this->get_account_balance($code);
            if($current_account_balance < 1){ throw new Exception('empty balance');} 
            if($current_account_balance < $amount) {throw new Exception('Insufficient balance');}
            if($this->preg_address($code, $recepteur)) {throw new Exception('invalid address');}
            $memo = htmlspecialchars($memo);
            $body = array (
                "type" => "send",
                "to" => $recepteur,
                "amount" => $amount,
                "currency" => $code,
                "idem" => $idem,
                "description" => $memo
            );
            $id_account = $this->get_account_id($code);
            $url    = url_recode($this->config['endpoint']['send_monney'], [$id_account]);
            $result = $this->request->make('POST', $body, $url);
            if(isset($result->errors[0]->id)){ throw new Exception("Error: ".$result->errors[0]->message);}
            // $id_transaction = "75d1d4be-28a4-5c87-bf07-43e4cf0d45a0";
            $id_transaction = $result->data->id;
            return $this->check_sending_transaction($code, $id_transaction);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    

    /*

    AUTRESSS

    */
    public function show_current_user(){
        try{
            $url = url_recode($this->config['endpoint']['current_user']);
            $res = $this->request->make('GET', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function list_accounts(){
        try{
            $url = url_recode($this->config['endpoint']['list_accounts']);
            $arrayobj = array();
            $pagination = true;
            do{
                $res = $this->request->make('GET', [], $url);
                if(isset($res->data)){
                    $arrayobj = array_merge($arrayobj, $res->data);
                }
                if (isset($res->pagination->next_uri) and !empty($res->pagination->next_uri)){
                    $url = url_recode($res->pagination->next_uri);
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return ($arrayobj);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function list_transactions($code){
        try{
            $id_account = $this->get_account_id($code);
            $url = url_recode($this->config['endpoint']['list_transactions'], [$id_account]);
            $arrayobj = array();
            $pagination = true;
            do{
                $result = $this->request->make('GET', [], $url);
                $arrayobj = array_merge($arrayobj, $result->data);
                if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                    $url = url_recode($result->pagination->next_uri);
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return $arrayobj; 
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


    // public function isset_transaction($code){
    //     try{
    //         $id_account = $this->get_account_id($code);
    //         $url = "/v2/accounts/$id_account/transactions";
        
    //         $timestamp = time();
    //         $sign = $this->getSign($timestamp, $method, $request_path, $body);
    //         $headers = array(
    //             'CB-ACCESS-KEY:'. $this->key,
    //             'CB-ACCESS-SIGN: '. $sign,
    //             'CB-ACCESS-TIMESTAMP: '. $timestamp,
    //             'CB-ACCESS-VERSION: 2021-10-05',
    //             'Content-Type: application/json'
    //         );
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //         curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
    //         $result = curl_exec($ch);
    //         $result = json_decode($result);
    //         return count($result->data);
    //     }catch(Exception $e){
    //         throw new Exception($e->getMessage());
    //     }
    // }


}

?>