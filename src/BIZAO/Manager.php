<?php

namespace Bizao;

use Exception;
use lab\App;
use lab\Request;
use DateTime;

use function lab\url_recode;

class Manager{

    private $app = null;
    private $config = null;
    private $business = null;
    private $request = null;
    private $countries =  null;
    
    public function __construct($business){
        $this->business =   $business;
        $this->app      =   new App('bizao');
        $this->config   =   $this->app->getAccess($this->business);
        $this->config['baseUrl'] = (isset($this->config['APP']['altUrl']) && ($this->config['APP']['altUrl']) ) ? $this->config['APP']['altUrl'] : $this->config['baseUrl'];
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Bizao');
        $this->countries =  $this->config['Countries'];
    }

    public function deposit($country = null, $operator = null, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null], $currency = null){
        try{
            // verifier le pays
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}
            // verifier l'operateur
            $operator = strtolower($operator);
            if(!isset($this->countries[$country]['operators'][$operator])){throw new Exception('Unknow operator');}
            $headers['mno-name'] = $operator;
            // verifier necessite de otp
            $operator = $this->countries[$country]['operators'][$operator];
            $fieldCount = 3; // correspond a la taille normal par defaut de $data
            if($operator['otp']){ 
                // check if mobile need or no an otp  code
                if(!isset($data['otp']) || empty($data['otp'])) throw new Exception('otp is required');
                $fieldCount = 4;
            }
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != $fieldCount)) throw new Exception('Incomplete input data');
            // verifier la currency
            $currency =  ($currency !== null) ? strtoupper($currency) : null;
            $index = array_search($currency, $operator['currencies']);
            if ($index !== false) {
                $currency = $operator['currencies'][$index];
            } else {
                if(!$currency){
                    // utilise la premiere currency par defaut
                    $currency = $operator['currencies'][0];
                }else{
                    // leve exception si currency renseignee est erronee
                    throw new Exception('Unknow currency');
                }
            }

            $body =[
                "currency"      => $currency,
                "order_id"      => $data['bash'],
                "amount"        => $data['amount'],
                "state"         => urlencode($data['phone'].'|'.$data['bash'].'|'.$data['amount']),
                "reference"     => $this->config['APP']['api']['reference'],
                "return_url"    => $this->config['APP']['callback']['return_url'],
                "cancel_url"    => $this->config['APP']['callback']['cancel_url'],
                "user_msisdn"   => $data['phone']
            ];

            if ($operator['otp']) {
                $body['otp_code'] = $data['otp'];
            }
            
            $additionalHeaders = [
                'channel' => 'tpe',
                'country-code' => $this->countries[$country]['iso']
            ];
            $headers = array_merge_recursive($headers, $additionalHeaders); 
    
            $url    =   url_recode($this->config['endpoint']['deposit']);
            $result =   $this->request->make('POST', $body, $url, $headers);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function check($country = null, $operator = null, $bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            // verifier le pays
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}
            // verifier l'operateur
            $operator = strtolower($operator);
            if(!isset($this->countries[$country]['operators'][$operator])){throw new Exception('Unknow operator');}
            $headers = [
                'mno-name' => $operator,
                'country-code' => $this->countries[$country]['iso']
            ];
            $url      =   url_recode($this->config['endpoint']['check'], [$bash]);
            $res      =   $this->request->make('GET', [], $url, $headers);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function reporting($dateStart = null, $dateEnd = null, $page_num = 1, $page_size = 25){
        try{
            if($page_num < 1){throw new Exception('Invalid page_num');}
            $reformattedDates = $this->reformatDate($dateStart, $dateEnd);
            if (!$reformattedDates) { throw new Exception('Invalid date format provided');}
            list($dateStart, $dateEnd) = $reformattedDates;
            $body = [
                "date_start" => $dateStart,
                "date_end"   => $dateEnd,
                "reference"  => $this->config['APP']['api']['reference'],
            ];
            $headers = [
                'report_type' => 'transaction_settlement_report',
                'page_num' => --$page_num,
                'page_size' => $page_size,
                'record_max_limit' => false
            ];
            $url    =   url_recode($this->config['endpoint']['reporting']);
            $result =   $this->request->make('POST', $body, $url, $headers);
            return $result;
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    private function reformatDate($date1, $date2) {
        $dateTime1 = DateTime::createFromFormat('Y-m-d H:i', $date1);
        if(!$dateTime1){ 
            if($dateTime1 = DateTime::createFromFormat('Y-m-d', $date1)){
                $dateTime1->setTime(0, 0); // Set the time to 00:00
            }
        }
        
        $dateTime2 = DateTime::createFromFormat('Y-m-d H:i', $date2);
        if(!$dateTime2){ 
            if($dateTime2 = DateTime::createFromFormat('Y-m-d', $date2)){
                $dateTime2->setTime(23, 59, 59); // Set the time to 23:59:59
            }
        }

        if ($dateTime1 && $dateTime2) {
            $millis1 = round($dateTime1->format('u') / 1000); 
            $millis2 = round($dateTime2->format('u') / 1000); 
            return [ 
                $dateTime1->format('Y-m-d H:i:s') . '.' . str_pad($millis1, 3, '0', STR_PAD_LEFT),
                $dateTime2->format('Y-m-d H:i:s') . '.' . str_pad($millis2, 3, '0', STR_PAD_LEFT),
            ];
        } else {
            return false;
        }

    }



    /***
     *  PARTIE DE GESTION CARTE VISA/MASTERCARD
     */
    
    public function card($country, $amount, $bash){
        try{
            // verifier le pays et les autres parametres
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}
            if(!isset($amount, $bash) || !$amount || !$bash) throw new Exception('Incomplete input data');
            
            // Affecter XOF a la currency
            $currency =  "XOF";
            $body =[
                "order_id"      => $bash,
                "reference"     => $this->config['APP']['api']['reference'],
                "amount"        => $amount,
                "currency"      => $currency,
                "return_url"    => $this->config['APP']['callback']['return_url'],
                "cancel_url"    => $this->config['APP']['callback']['cancel_url'],
                "state"         => urlencode($bash.'|'.$amount),
            ];

            $headers = [
                'country-code' => $this->countries[$country]['iso'],
                'category' => 'BIZAO',
            ];
    
            $url    =   url_recode($this->config['endpoint']['card_deposit']);
            $result =   $this->request->make('POST', $body, $url, $headers);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function cardCheck($country = null, $bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            // verifier le pays
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}

            $headers = [
                'country-code' => $this->countries[$country]['iso']
            ];
            $url      =   url_recode($this->config['endpoint']['card_check'], [$bash]);
            $res      =   $this->request->make('GET', [], $url, $headers);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }


    public function withdraw($country = null, $operator = null, $data = ["phone" => null, "amount" => null, "bash" => null], $currency = null){
        try{
            // verifier le pays
            if(!isset($this->countries[$country])){throw new Exception('Unknow country');}
            // verifier si le pays fait du Bulk
            if(!isset($this->countries[$country]['bulk-transfer']) || !$this->countries[$country]['bulk-transfer']){throw new Exception('Bulk transfer not available in this country');}
            // verifier l'operateur
            $operator = strtolower($operator);
            if(!isset($this->countries[$country]['operators'][$operator])){throw new Exception('Unknow operator');}
            $operatorSave = $operator;
            
            $operator = $this->countries[$country]['operators'][$operator];
            
            $fieldCount = 3;
            if(!isset($data['phone'], $data['amount'], $data['bash']) || (count($data) != $fieldCount)) throw new Exception('Incomplete input data');
            // verifier la currency
            $currency =  ($currency !== null) ? strtoupper($currency) : null;
            $index = array_search($currency, $operator['currencies']);
            if ($index !== false) {
                $currency = $operator['currencies'][$index];
            } else {
                if(!$currency){
                    // utilise la premiere currency par defaut
                    $currency = $operator['currencies'][0];
                }else{
                    // leve exception si currency renseignee est erronee
                    throw new Exception('Unknow currency');
                }
            }

            $body =[
                "currency"      => $currency,
                "reference"     => $this->config['APP']['api']['reference'],
                "batchNumber"      => $data['bash'],
                "state"         => urlencode($data['phone'].'|'.$data['bash'].'|'.$data['amount']),
                "data" => [
                    [
                        "id"=> $data['bash'],
                        "beneficiaryFirstName"=> "Bizao",
                        "beneficiaryLastName"=> "Hub",
                        "beneficiaryAddress"=> "Rue 29 angle 20, Dakar",
                        "beneficiaryMobileNumber"=> $data['phone'],
                        "amount"=> $data['amount'],
                        "feesApplicable"=> "YES",
                        "mno"=> $operatorSave
                    ]
                ]
            ];
            
            $headers = [
                'country-code' => $this->countries[$country]['iso'],
                'channel' => 'web',
                'type' => 'bulk'
            ];
    
            $url    =   url_recode($this->config['endpoint']['bulk']);
            $result =   $this->request->make('POST', $body, $url, $headers);
            return $result;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function bulkCheck($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $headers = [
                'channel' => 'web',
                'type' => 'bulk'
            ];
            $url  =   url_recode($this->config['endpoint']['bulk_check'], [$bash]);
            $res  =   $this->request->make('GET', [], $url, $headers);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function balance(){
        try{
            $headers = [
                'type' => 'bulk'
            ];
            $account = $this->config['APP']['api']['account'];
            $url  =   url_recode($this->config['endpoint']['bulk_balance'], [$account]);
            $res  =   $this->request->make('GET', [], $url, $headers);
            return $res;
        }catch(Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }
}