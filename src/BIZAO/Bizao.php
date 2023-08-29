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
     *  PARTIE DE GESTION MOBILE MONEY (PAYMENT)
     */
    public function deposit($country = null, $operator = null, $data = ["phone" => null, "amount" => null, "bash" => null, "otp" => null], $currency = null){
        try{
            $res = $this->manager->deposit($country, $operator, $data, $currency);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            unset($res['meta']);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 400, "message" => $e->getMessage()];
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

    public function operationStatus($data = []){
        try{
            // Verifier l'existance des champs clés
            if(!isset($data['meta'], $data['order-id']) || 
            !count($data['meta']) ||
            !$data['order-id']){ 
                throw new Exception('invalid callback data receive');
            }
            // Extraire les donnees importantes necessaires pour un check
            $bash = $data['order-id'];
            $meta = explode('_', $data['meta']['source']);
            $country = strtoupper($meta[0]);
            $operator = $meta[1];
            $res = (array) $this->manager->check($country, $operator, $bash);
            unset($res['meta']);
            return $res;
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return $e->getMessage();
        }
    }

    /***
     *  PARTIE DE GESTION CARTE VISA/MASTERCARD
     */

    public function card($country = null, $amount = null, $bash = null){
        try{
            $res = $this->manager->card($country, $amount, $bash);
            $res = (array) $res;
            // if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            // unset($res['meta']);
            // return ["code" => 200, "data" => $res]; 
            return $res;
        }catch(Exception $e){
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }

    public function cardCheck($country = null, $bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->cardCheck($country, $bash);
            $res = (array) $res;
            if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            unset($res['meta']);
            return ["code" => 200, "data" => $res]; 
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function cardOperationStatus($data = []){
        try{
            // Verifier l'existance des champs clés
            if(!isset($data['meta'], $data['order-id']) || 
            !count($data['meta']) ||
            !$data['order-id']){ 
                throw new Exception('invalid callback data receive');
            }
            // Extraire les donnees importantes necessaires pour un check
            $bash = $data['order-id'];
            $meta = explode('_', $data['meta']['source']);
            $country = strtoupper($meta[0]);
            $res = (array) $this->manager->cardCheck($country, $bash);
            unset($res['meta']);
            return $res;
        }catch(Exception $e){
            // throw new Exception($e->getMessage());
            return $e->getMessage();
        }
    }


    /***
     *  PARTIE DE GESTION MOBILE MONEY (TRANSFERT)
     */
    public function withdraw($country = null, $operator = null, $data = ["phone" => null, "amount" => null, "bash" => null], $currency = null){
        try{
            $res = $this->manager->withdraw($country, $operator, $data, $currency);
            $res = (array) $res;
            // if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            // unset($res['meta']);
            // return ["code" => 200, "data" => $res]; 
            return $res;
        }catch(Exception $e){
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }

    public function bulkCheck($bash = null){
        try{
            if(!isset($bash) || !$bash) throw new Exception('bash param is mandatory.');
            $res = $this->manager->bulkCheck($bash);
            $res = (array) $res;
            // if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            // unset($res['meta']);
            // return ["code" => 200, "data" => $res]; 
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

    public function balance(){
        try{
            $res = $this->manager->balance();
            $res = (array) $res;
            // if(isset($res['requestError'])) throw new Exception('Bizoa execution failed exception.');
            // unset($res['meta']);
            // return ["code" => 200, "data" => $res]; 
            return $res;
        }catch(Exception $e){
            return ["code" => 412, "error" => $e->getMessage()];
        }
    }

}