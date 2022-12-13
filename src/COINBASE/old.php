<?php









    # NOTE 1: https://developers.coinbase.com/api/v2?shell#show-current-user
    # NOTE 2: https://github.com/ajaxorg/ace/wiki/Default-Keyboard-Shortcuts
    # NOTE 3: https://bitcoin.stackexchange.com/users/signup?ssrc=head&returnurl=https%3a%2f%2fbitcoin.stackexchange.com%2fquestions%2f60567%2fhow-to-check-confirmations-number-programmatically
    # NOTE 4: https://www.blockchain.com/btc/tx/5b3af2770d1636026f31f584a68836950d2c1fdfd2d3e9ab775926af1d254eb5
    # NOTE 5: https://www.coinbase.com/signin?return_to=%2Fdashboard
    
    // include_once('../../BK_ERROR.php');
    
    class Coinbaseffkdjjkdkdnk{
        
        private $secret = null;
        private $key = null;
        private $path_url = 'https://api.coinbase.com';
        private $errors = null;
        
        public function __construct(){
            $this->secret = "g10oQ9ZgHj3BxiicDLqZjnFKV5dP2PjD";
            $this->key = "SrQUSuU6gUxcRY8j";
            $this->errors = new BK_ERROR();
        }
        
        private function error($error_code, $extra = null){
            return $this->errors->yield($error_code, $extra);
        }
        
        private function getSign($timestamp, $method, $request_path, $body){
            $prehash = $timestamp.$method.$request_path.$body;
            return hash_hmac("sha256", $prehash, $this->secret);
        }
        
        public function show_current_user(){
            $method = "GET";
            $body = "";
            $request_path = "/v2/user";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            return json_decode($result);
        }
        
        public function list_accounts(){
            $method = "GET";
            $body = "";
            $request_path = "/v2/accounts";
            
            $arrayobj = array();
            $pagination = true;
            do{
                $timestamp = time();
                $sign = $this->getSign($timestamp, $method, $request_path, $body);
                $headers = array(
                    'CB-ACCESS-KEY:'. $this->key,
                    'CB-ACCESS-SIGN: '. $sign,
                    'CB-ACCESS-TIMESTAMP: '. $timestamp,
                    'CB-ACCESS-VERSION: 2021-10-05',
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
                $result = curl_exec($ch);
                $result = json_decode($result);
                if(isset($result->data)){
                    $arrayobj = array_merge($arrayobj, $result->data);
                }
                if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                    $request_path = $result->pagination->next_uri;
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return ($arrayobj);
        }
        
        public function get_account_id($code){
            $accounts = $this->list_accounts();
            $len = count($accounts);
            foreach($accounts as $account){
                $account_code = $account->currency->code;
                if($account_code == $code){
                    return $account->id;
                }
            }
            return null;
        }

        public function show_an_account($code){
            $method = "GET";
            $body = "";
            $id_account = $this->get_account_id($code);
            $request_path = "/v2/accounts/".$id_account;
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            return json_decode($result);
        }
        
        public function list_addresses($code){
            $method = "GET";
            $body = "";
            $id_account = $this->get_account_id($code);
            $request_path = "/v2/accounts/$id_account/addresses";
            $arrayobj = array();
            $pagination = true;
            
            do{
                $timestamp = time();
                $sign = $this->getSign($timestamp, $method, $request_path, $body);
                $headers = array(
                    'CB-ACCESS-KEY:'. $this->key,
                    'CB-ACCESS-SIGN: '. $sign,
                    'CB-ACCESS-TIMESTAMP: '. $timestamp,
                    'CB-ACCESS-VERSION: 2021-10-05',
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
                $result = curl_exec($ch);
                $result = json_decode($result);
                $arrayobj = array_merge($arrayobj, $result->data);
                
                if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                    $request_path = $result->pagination->next_uri;
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return $arrayobj;
        }
        
        public function check_crypto_code_validation($code){
            $codes = $this->list_accounts_code();
            if(in_array($code, $codes)){
                return true;
            }
            return false;
        }
        
        /*
        public function create_address($code){
            $code = strtoupper($code);
            //check_crypto_code_validation()
            if(!$this->check_crypto_code_validation($code)){
                throw new Exception('Error: ' .$code. ' is not a valid crypto code.');
            }
            $method = "POST";
            $data = array (
                'name' => "New received $code address",
            );
            $body = json_encode($data);
            $id_account = $this->get_account_id(strtoupper($code));
            $request_path = "/v2/accounts/$id_account/addresses";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            $adress = json_decode($result);
            
            return $adress->data->address;
        
        }
        */
        
        public function create_address($code){
            $code = strtoupper($code);
            //check_crypto_code_validation()
            if(!$this->check_crypto_code_validation($code)){
                // throw new Exception('Error: ' .$code. ' is not a valid crypto code.');
                return $this->error(100, $code);
            }
            $method = "POST";
            $data = array (
                'name' => "New received $code address",
            );
            $body = json_encode($data);
            $id_account = $this->get_account_id(strtoupper($code));
            $request_path = "/v2/accounts/$id_account/addresses";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            $address = json_decode($result);
            if(isset($address->data->destination_tag)){
                $tag = $address->data->destination_tag;
                return array('address' => $address->data->address, 'tag' => $tag);
            }
            return array('address' => $address->data->address);;
        
        }
        
        public function list_transactions($code){
            $method = "GET";
            $body = "";
            $id_account = $this->get_account_id($code);
            $request_path = "/v2/accounts/$id_account/transactions";
            $arrayobj = array();
            $pagination = true;
            do{
                $timestamp = time();
                $sign = $this->getSign($timestamp, $method, $request_path, $body);
                $headers = array(
                    'CB-ACCESS-KEY:'. $this->key,
                    'CB-ACCESS-SIGN: '. $sign,
                    'CB-ACCESS-TIMESTAMP: '. $timestamp,
                    'CB-ACCESS-VERSION: 2021-10-05',
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
                $result = curl_exec($ch);
                $result = json_decode($result);
                $arrayobj = array_merge($arrayobj, $result->data);
                if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                    $request_path = $result->pagination->next_uri;
                }else{
                    $pagination = false;
                }
            }while($pagination);
            return $arrayobj; 
        }
        
        // public function isset_transaction($code){
        //     $method = "GET";
        //     $body = "";
        //     $id_account = $this->get_account_id($code);
        //     $request_path = "/v2/accounts/$id_account/transactions";
           
        //     $timestamp = time();
        //     $sign = $this->getSign($timestamp, $method, $request_path, $body);
        //     $headers = array(
        //         'CB-ACCESS-KEY:'. $this->key,
        //         'CB-ACCESS-SIGN: '. $sign,
        //         'CB-ACCESS-TIMESTAMP: '. $timestamp,
        //         'CB-ACCESS-VERSION: 2021-10-05',
        //         'Content-Type: application/json'
        //     );
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //     curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //     curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
        //     $result = curl_exec($ch);
        //     $result = json_decode($result);
        //     return count($result->data);
        // }
        
        public function get_history($start_time, $end_time){
            if(!$this->validate_date($start_time)){
                // throw new Exception('Error: start date format not matched #(Y-m-d).');
                return $this->error(200);
            }elseif(!$this->validate_date($end_time)){
                // throw new Exception('Error: end date format not matched #(Y-m-d).');
                return $this->error(201);
            }
            $start_time_timestamp = strtotime($start_time);
            $end_time_timestamp = strtotime($end_time);
            if($start_time_timestamp > $end_time_timestamp){
                // throw new Exception('Error: the start date must not exceed the end date.');
                return $this->error(202);
            }
            $method = "GET";
            $body = "";
            $request_path = "/v2/accounts";
            $arrayobj = array();
            $accounts = array();
            $pagination = true;
            do{
                $timestamp = time();
                $sign = $this->getSign($timestamp, $method, $request_path, $body);
                $headers = array(
                    'CB-ACCESS-KEY:'. $this->key,
                    'CB-ACCESS-SIGN: '. $sign,
                    'CB-ACCESS-TIMESTAMP: '. $timestamp,
                    'CB-ACCESS-VERSION: 2021-10-05',
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
                $result = curl_exec($ch);
                $result = json_decode($result);
                
                if(isset($result->data)){
                    $accounts = array_merge($accounts, $result->data);
                }
                if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                    $request_path = $result->pagination->next_uri;
                }else{
                    $pagination = false;
                }
            }while($pagination);
            // return ($arrayobj);
            $list_trans = array();
            foreach($accounts as $account){
                $account_code = $account->currency->code;
                $id_account = $account->id;
                $request_path = "/v2/accounts/$id_account/transactions";
                $transactions = array();
                $pagination = true;
                do{
                    $timestamp = time();
                    $sign = $this->getSign($timestamp, $method, $request_path, $body);
                    $headers = array(
                        'CB-ACCESS-KEY:'. $this->key,
                        'CB-ACCESS-SIGN: '. $sign,
                        'CB-ACCESS-TIMESTAMP: '. $timestamp,
                        'CB-ACCESS-VERSION: 2021-10-05',
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
                    $result = curl_exec($ch);
                    $result = json_decode($result);
                    if(isset($result->data)){
                        $transactions = array_merge($arrayobj, $result->data);
                    }
                    if (isset($result->pagination->next_uri) and !empty($result->pagination->next_uri)){
                        $request_path = $result->pagination->next_uri;
                    }else{
                        $pagination = false;
                    }
                }while($pagination);
                if(!empty($transactions)){
                    foreach($transactions as $transaction){
                        $list_trans[] = $transaction;
                    }
                }
            }
            // return $list_trans;
            
            foreach($list_trans as $transaction){
                $created_at = date("Y-m-d", strtotime($transaction->created_at));
                $created_timestamp = strtotime($created_at);
                if(($created_timestamp < $start_time_timestamp) || ($created_timestamp > $end_time_timestamp)){
                    continue;
                }
                $amount = $transaction->amount->amount;
                $code = $transaction->amount->currency;
                $operation = ($amount < 0)?1:0;
                $amount = ($amount < 0)?(-1 * $amount):$amount;
                $status = $transaction->status;
                $status_bin = ($status == 'completed')?1:(($status == 'pending')?0:-1); 
                $id = $transaction->id;
                $description = (empty($transaction->description))?null:$transaction->description;
                if(isset($transaction->to->address)){
                    $address = $transaction->to->address;
                }elseif(isset($transaction->to->email)){
                     $address = $transaction->to->email;
                }
                
                $line['code'] = $code;
                $line['id'] = $id;
                $line['date'] = $created_at;
                $line['address'] = $address;
                $line['statut'] = $status_bin;
                $line['amount'] = $amount;
                $line['operation'] = $operation;
                $line['description'] = $description;
                array_push($arrayobj, $line);
            }
            return $arrayobj;
            
            
        }
        
        private function validate_date($date, $format = 'Y-m-d'){
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) === $date;
        }
        
        public function get_asset_history($code = null, $start_time, $end_time){
            $code = strtoupper($code);
            //check_crypto_code_validation()
            if(!$this->check_crypto_code_validation($code)){
                // throw new Exception('Error: ' .$code. ' is not a valid crypto code.');
                return $this->error(100, $code);
            }
            $arrayobj = array();
            if(!$this->validate_date($start_time)){
                // throw new Exception('Error: start date format not matched #(Y-m-d).');
                return $this->error(200);
            }elseif(!$this->validate_date($end_time)){
                // throw new Exception('Error: end date format not matched #(Y-m-d).');
                return $this->error(201);
            }
            
            $start_time_timestamp = strtotime($start_time);
            $end_time_timestamp = strtotime($end_time);
            if($start_time_timestamp > $end_time_timestamp){
                // throw new Exception('Error: the start date must not exceed the end date.');
                return $this->error(202);
            }
            
            $line = [];
            $transactions = $this->list_transactions($code);
            foreach($transactions as $transaction){
                $created_at = date("Y-m-d", strtotime($transaction->created_at));
                $created_timestamp = strtotime($created_at);
                if(($created_timestamp < $start_time_timestamp) || ($created_timestamp > $end_time_timestamp)){
                    continue;
                }
                $amount = $transaction->amount->amount;
                $operation = ($amount < 0)?1:0;
                $amount = ($amount < 0)?(-1 * $amount):$amount;
                $status = $transaction->status;
                $status_bin = ($status == 'completed')?1:(($status == 'pending')?0:-1); 
                $id = $transaction->id;
                $description = (empty($transaction->description))?null:$transaction->description;
                if(isset($transaction->to->address)){
                    $address = $transaction->to->address;
                }elseif(isset($transaction->to->email)){
                     $address = $transaction->to->email;
                }
                
                $line['code'] = $code;
                $line['id'] = $id;
                $line['date'] = $created_at;
                $line['address'] = $address;
                $line['statut'] = $status_bin;
                $line['amount'] = $amount;
                $line['operation'] = $operation;
                $line['description'] = $description;
                array_push($arrayobj, $line);
            }
            return $arrayobj;
            // return $transactions;
        }
        
        public function list_accounts_code(){
            $accounts = $this->list_accounts();
            $len = count($accounts);
            $arrayobj = array();
            foreach($accounts as $account){
                $account_code = $account->currency->code;
                array_push($arrayobj, $account_code);
            }
            return $arrayobj;
        }
        
        public function get_address_id($address, $code){
            $code = strtoupper($code);
            $addresses = $this->list_addresses($code);
            if(count($addresses)){
                foreach($addresses as $self){
                    $self_address = $self->address;
                    if($self_address == $address){
                        return $self->id;
                    }
                }
            }
            return null;
        }
        
        public function check_transaction($code, $address, $amount){
            $code = strtoupper($code);
            //check_crypto_code_validation()
            if(!$this->check_crypto_code_validation($code)){
                // throw new Exception('Error: ' .$code. ' is not a valid crypto code.');
                return $this->error(100, $code);
            }
            // check address compliant with the code
            if(!$this->preg_address($code, $address)){
                // throw new Exception('Error : Address not compliant with the '. $code .' cryptomoney');
                return $this->error(102, $code);
            }
            //check amount
            if($amount < 0){
                // throw new Exception('Error : The value of the amount must be positive');
                return $this->error(300);
            }
            if($amount == 0){
                // throw new Exception('Error : The value of the amount cannot be null');
                return $this->error(301, $code);
            }
            
            $cryto_not_need_ids_to_check_transaction = array('BTC');
            if (!in_array($code, $cryto_not_need_ids_to_check_transaction)){
                $address = $this->get_address_id($address, $code);
                
            }
        
            $method = "GET";
            $body = "";
            $id_account = $this->get_account_id(strtoupper($code));
            $request_path = "/v2/accounts/$id_account/addresses/$address/transactions";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            $result = json_decode($result);
            // return $result;
            $payment = 0;
            if(isset($result->errors[0]->id)){
                // throw new Exception('request path not found.');
                return $this->error(404);
                // return $result;
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
                return ($payment)
                        ?(($status != 'completed')
                            ?array('payment' => $payment, 'tx_id' => $tx_id, 'amount_received' => $amount_received, 'statut' => 0, 'payment_weight' => $payment_weight, 'confirmations' => $confirmations)
                            :array('payment' => $payment, 'tx_id' => $tx_id, 'amount_received' => $amount_received, 'statut' => 1, 'payment_weight' => $payment_weight))
                        :array('payment' => $payment);
            }
        }
        
        private function response ($result) {   
            header("Content-Type:application/json");
            $response['Confirmations'] = $result;
            $json_response = json_encode($response);
            return $json_response;
        }
        
        public function blockchain_confirmations($tx_hash){
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




        public function get_spot_price($base, $currency){
            $method = "GET";
            $body = "";
            $currency_pair = "$base-$currency";
            $request_path = "/v2/prices/$currency_pair/spot";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            $result = json_decode($result);
            if(isset($result->errors[0]->id)){
                // throw new Exception('Invalid current pair for '.$currency_pair);
                return $this->error(101, $currency_pair);
            }else{
                return $result->data->amount;
            }
        }
        
        public function asset_data($code, $currency = 'USD'){
            $code = strtoupper($code);
            if(!$this->check_crypto_code_validation($code)){
                // throw new Exception('Error: ' .$code. ' is not a valid crypto code.');
                return $this->error(100, $code);
            }
            
            // Addresses 
            $address = $this->create_address($code);
            
            // balance
            $account = $this->show_an_account($code);
            $balance = $account->data->balance->amount;
            
            //spot price
            $spot_price = $this->get_spot_price($code, $currency);
            
            return array(
                'address' => $address,
                'balance' => $balance,
                'spot_price' => $spot_price,
                'currency_pair' => "$code/$currency",
                );
        }
        
        public function convert_to_usd($code, $amount){
            $code = strtoupper($code);
            if($code == 'USD'){
                return $amount;
            }else{
                $spot_price = $this->get_spot_price($code, 'USD');
                return  $amount * $spot_price;
            }
            return  0;
        }
        
        public function get_accounts_balance(){
            
            $accounts = $this->list_accounts();
            
            foreach($accounts as $account){
                $amount = $account->balance->amount;
                $currency =  $account->currency->code;
                $account_balance = 0;
                if($amount > 0){
                    // array_push($arrayobj, $amount);
                    //if($currency != 'USD'){
                    $balance_USD = $this->convert_to_usd($currency, $amount);
                    $account_balance += $balance_USD;
                    //}
                }
            }
            return array('balance' => $account_balance, 'currency' => 'USD');
        }
        
        public function get_account_balance($code){
            $code = strtoupper($code);
            $account = $this->show_an_account($code);
            
            return $account->data->balance->amount;
        }
        
        public function send_monney($code, $recepteur, $amount, $memo){
            $code = strtoupper($code);
            //check_crypto_code_validation()
            if(!$this->check_crypto_code_validation($code)){
                // throw new Exception('Error: ' .$code. ' is not a valid crypto code.');
                return $this->error(301, $code);
            }
            
            $current_account_balance = $this->get_account_balance($code);
            
            if($current_account_balance > 0){
                
                if($current_account_balance > $amount){
                  
                    if($this->preg_address($code, $recepteur)){
                        
                        $memo = htmlspecialchars($memo);
                        
                        $method = "POST";
                        $data = array (
                            "type" => "send",
                            "to" => $recepteur,
                            "amount" => $amount,
                            "currency" => $code,
                            "idem" => "9316dd16-0c05",
                            "description" => $memo
                        );
                        
                        $body = json_encode($data);
                        $id_account = $this->get_account_id(strtoupper($code));
                        $request_path = "/v2/accounts/$id_account/transactions"; 
                        $timestamp = time();
                        $sign = $this->getSign($timestamp, $method, $request_path, $body);
                        $headers = array(
                            'CB-ACCESS-KEY:'. $this->key,
                            'CB-ACCESS-SIGN: '. $sign,
                            'CB-ACCESS-TIMESTAMP: '. $timestamp,
                            'CB-ACCESS-VERSION: 2021-10-05',
                            'Content-Type: application/json'
                        );
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                        curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
                        $result = curl_exec($ch);
                        $result = json_decode($result);
                        if(isset($result->errors[0]->id)){
                            // throw new Exception("Error: ".$result->errors[0]->message);
                            return $this->error(401, $result->errors[0]->message);
                        }else{
                            // $id_transaction = "75d1d4be-28a4-5c87-bf07-43e4cf0d45a0";
                            $id_transaction = $result->data->id;
                            return $this->check_sending_transaction($code, $id_transaction);
                        }
                    }else{
                        // throw new Exception('Error : Address not compliant with the '. $code .' cryptomoney');
                        return $this->error(102, $code);
                    }
                }else{
                    // throw new Exception('Error : Insufficient balance for this transaction');
                    return $this->error(302);
                }
            }else{
                // throw new Exception('Error : Your '. $code.' account balance is empty');
                return $this->error(303, $code);
            }
        }
        
        public function check_sending_transaction($code = null, $id_transaction = null){
            $code = strtoupper($code);
            $id_account = $this->get_account_id($code);
            $method = "GET";
            $body = "";
            $currency_pair = "$base-$currency";
            $request_path = "/v2/accounts/$id_account/transactions/$id_transaction";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            $result = json_decode($result);
            $status = $result->data->status;
            $status_bin = ($status == 'completed')?1:0; 
            $confirmations = $result->data->network->confirmations;
            return array('tx_id' => $id_transaction, 'statut' => $status_bin, 'confirmations' => $confirmations);
        }
        
        public function get_deposit($code = null, $id_transaction = null){
            $code = strtoupper($code);
            $id_account = $this->get_account_id($code);
            $method = "GET";
            $body = "";
            $currency_pair = "$base-$currency";
            $request_path = "/v2/accounts/$id_account/transactions/$id_transaction";
            $timestamp = time();
            $sign = $this->getSign($timestamp, $method, $request_path, $body);
            $headers = array(
                'CB-ACCESS-KEY:'. $this->key,
                'CB-ACCESS-SIGN: '. $sign,
                'CB-ACCESS-TIMESTAMP: '. $timestamp,
                'CB-ACCESS-VERSION: 2021-10-05',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $this->path_url.$request_path);
            $result = curl_exec($ch);
            $result = json_decode($result);
            return $result;
        }
        
        public function list_accounts_regex(){
            $accounts = $this->list_accounts();
            foreach($accounts as $account){
                $code = $account->currency->code;
                $regex = $account->currency->address_regex;
                echo('case \''.$code.'\' : $regex = "'.$regex.'";   break;');
                echo('<br>');
            }
        }
        
        private function preg_address($code ,$address){
            $code = strtoupper($code);
            switch($code){
                // case 'BTC' : $regex = "^([13][a-km-zA-HJ-NP-Z1-9]{25,34})|^(bc1([qpzry9x8gf2tvdw0s3jn54khce6mua7l]{39}|[qpzry9x8gf2tvdw0s3jn54khce6mua7l]{59}))$";   break;
                // case 'DOGE' : $regex = "^((D|A|9)[a-km-zA-HJ-NP-Z1-9]{25,34})$";    break;
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
        }
        
        // End Coinbase class
    }
    
    
    class BK_ERROR{
        
        private $error = array(
            
            /* crypto */ 
            '100' => array('code' => 'BK_100', 'description' => "Your input is not a valid crypto code."),
            '101' => array('code' => 'BK_101', 'description' => "Invalid current pair."),
            '102' => array('code' => 'BK_102', 'description' => "Address not compliant with the your cryptomoney."),
            
            /* Date  */ 
            '200' => array('code' => 'BK_200', 'description' => "start date format not matched #(Y-m-d)."),
            '201' => array('code' => 'BK_201', 'description' => "end date format not matched #(Y-m-d)."),
            '202' => array('code' => 'BK_202', 'description' => "the start date must not exceed the end date."),
            
            /* Digits */
            '300' => array('code' => 'BK_300', 'description' => "The value of the amount must be positive."),
            '301' => array('code' => 'BK_301', 'description' => "The value of the amount cannot be null."),
            '302' => array('code' => 'BK_302', 'description' => "Insufficient balance for this transaction."),
            '303' => array('code' => 'BK_303', 'description' => "Your account balance is empty."),
            
            /* path */
            '401' => array('code' => 'BK_401', 'description' => "Unknow error."),
            '404' => array('code' => 'BK_404', 'description' => "Request path not found."),

            );
        
        public function __construct(){
            
        }
        
        public function yield($code, $extra = null){
            if($extra){
                switch($code){
                    case 100 :  $description = "$extra is not a valid crypto code."; break;
                    case 101 :  $description = "Invalid current pair for $extra ."; break;
                    case 102 :  $description = "Address not compliant with the  $extra cryptomoney."; break;
                    case 303 :  $description = "Your $extra account balance is empty."; break;
                    case 401 :  $description = "$extra"; break;
                }
                $this->error[$code]['description'] = $description;
            }
            return array('error' => $this->ToObject($this->error[$code]));
        }
        
        private function ToObject($Array) {
            $object = new stdClass();
            foreach ($Array as $key => $value) {
                if (is_array($value)) {
                    $value = ToObject($value);
                }
                $object->$key = $value;
            }
            return $object;
        }
        
    }

?>