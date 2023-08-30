<?php
namespace Bizao;

use Exception;

class Bizao{

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

    /***
     * 
     *  PARTIE DE GESTION 
     *  MOBILE MONEY PAYMENT ET TRANSFER BULK
     */ 
    /** DEBUT 1ERE PARTIE PAYMENT */
    public function deposit($country = null, $operator = null, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null], $currency = null){
        try{
            $res = $this->manager->deposit($country, $operator, $data, $currency);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            unset($res['meta']);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "message" => $e->getMessage()];
        }
    }

    public function check($country = null, $operator = null, $bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->check($country, $operator, $bash);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            unset($res['meta']);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function reporting($dateStart = null, $dateEnd = null, $page_num = 1, $page_size = 25){
        try{
            $res = $this->manager->reporting($dateStart, $dateEnd, $page_num, $page_size);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            
            $result['data'] = $res['data'];
            $result['page_num'] = $res['meta']->page_num + 1;
            $result['page_size'] = $res['meta']->page_size;
            $result['record_count'] = $res['meta']->record_count;
            $result['total_record_count'] = $res['meta']->total_record_count;
            $result['total_page_count'] = $res['meta']->total_page_count;
            $result['next_page'] = ($res['meta']->page_num + 1 < $res['meta']->total_page_count) ? $res['meta']->page_num + 2 : null;

            return array_merge_recursive(["code" => 200] , $result); 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    /** FIN 1ERE PARTIE PAYMENT */

    /** DEBUT 2EME PARTIE TRANSFER BULK */
    public function withdraw($country = null, $operator = null, $data = ["phone" => null, "amount" => null, "bash" => null], $currency = null){
        try{
            $res = $this->manager->withdraw($country, $operator, $data, $currency);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            // unset($res['meta']);
            $res = array(
                'batchNumber' => $res['meta']->batchNumber,
                'order_id' => $res['meta']->batchNumber,   // $res['data'][0]->order_id,
                'mno' => $res['data'][0]->mno,
                'date' => $res['data'][0]->date,
                'beneficiaryMobileNumber' => $res['data'][0]->beneficiaryMobileNumber,
                'toCountry' => $res['data'][0]->toCountry,
                'feesApplicable' => $res['data'][0]->feesApplicable,
                'amount' => $res['data'][0]->amount,
                'fees' => $res['data'][0]->fees,
                'status' => $res['data'][0]->status,
                'currency' => $res['meta']->currency
            );
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "message" => $e->getMessage()];
        }
    }

    public function bulkCheck($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->bulkCheck($bash);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            $res = array(
                'batchNumber' => $res['meta']->batchNumber,
                'order_id' => $res['meta']->batchNumber,   // $res['data'][0]->order_id,
                'mno' => $res['data'][0]->mno,
                'date' => $res['data'][0]->date,
                'beneficiaryMobileNumber' => $res['data'][0]->beneficiaryMobileNumber,
                'toCountry' => $res['data'][0]->toCountry,
                'feesApplicable' => $res['data'][0]->feesApplicable,
                'amount' => $res['data'][0]->amount,
                'fees' => $res['data'][0]->fees,
                'status' => $res['data'][0]->status,
                'currency' => $res['meta']->currency
            );
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function balance(){
        try{
            $res = $this->manager->balance();
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            $res = array(
                'status' => $res['accounts'][0]->status,
                'currency' => $res['accounts'][0]->currency,
                'balance' => $res['accounts'][0]->balance
            );
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }
    /** FIN 2EME PARTIE TRANSFER BULK */




    /***
     * 
     *  PARTIE DE GESTION 
     *  CARTE VISA/MASTERCARD
     *  
     */
    public function card($amount = null, $bash = null){
        try{
            $res = $this->manager->card($amount, $bash);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            return ["code" => 200, "data" => $res];
        }catch(Exception $e){
            return ["code" => 412, "message" => $e->getMessage()];
        }
    }

    public function cardCheck($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->cardCheck($bash);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            unset($res['meta']);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }




    /***  
     * 
     * ANNALYSE STATUT CALLBACK
     * MOBILE MONEY (PAYMENT, TRANSFER, CARD) 
     * 
     */
    public function operationStatus($data = []){
        try{

            // Verifier l'existance des champs clés || Depot
            if(isset($data['meta'], $data['order-id']) && 
            count($data['meta']) &&
            $data['order-id']){ 

                // Extraire les donnees importantes necessaires pour un check payment ou card
                $bash = $data['order-id'];
                $meta = explode('_', $data['meta']['source']);
                $country = strtoupper($meta[0]);
                $operator = $meta[1];

                if($operator !== 'Visa'){
                    // CAS MOBILE
                    $res = (array) $this->manager->check($country, $operator, $bash);
                }else{
                    // CAS MOBILE CARD
                    $res = (array) $this->manager->cardCheck($bash);
                }

                unset($res['meta']);
                return $res;

            }elseif(!isset($data['meta'], $data['data']) || 
                !count($data['meta']) ||
                !$data['meta']['batchNumber']){ {
                    // Verifier l'existance des champs clés || bulk
                    throw new Exception('invalid callback data receive');
                }
            }

            // Extraire les donnees importantes necessaires pour un check bulk
            $bash = $data['meta']['batchNumber'];
            $res = (array) $this->manager->bulkCheck($bash);
            $res = array(
                'batchNumber' => $res['meta']->batchNumber,
                'order_id' => $res['meta']->batchNumber,   // $res['data'][0]->order_id,
                'mno' => $res['data'][0]->mno,
                'date' => $res['data'][0]->date,
                'beneficiaryMobileNumber' => $res['data'][0]->beneficiaryMobileNumber,
                'toCountry' => $res['data'][0]->toCountry,
                'feesApplicable' => $res['data'][0]->feesApplicable,
                'amount' => $res['data'][0]->amount,
                'fees' => $res['data'][0]->fees,
                'status' => $res['data'][0]->status,
                'currency' => $res['meta']->currency
            );
            return $res;

        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return $e->getMessage();
            // return false;
        }
    }

}