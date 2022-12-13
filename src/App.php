<?php

namespace lab;
use Exception;

class App{

    private $lib = null;

    public function __construct($lib = null){
        $this->lib = strtoupper($lib);
    }

    public function getAccess($business = null, $format = 'json'): Array{
        try{
            switch($format){
                case 'json' : 
                    $FILE     = file_get_contents(dirname(__DIR__)."/.config.json");
                    $ACCESS   = json_decode( $FILE , true);
                    break;
                default : throw new Exception("Invalid file format");
            }
            if (!isset($ACCESS[$this->lib])) throw new Exception("$this->lib is not configured as librarie");
            if (!isset($ACCESS[$this->lib]['APP'][$business])) throw new Exception("$business is not configured as app");
            $CONFIGS = $ACCESS[$this->lib];
            unset($CONFIGS['APP']);
            $CONFIGS['APP'] = $ACCESS[$this->lib]['APP'][$business];
            $CONFIGS['APP']['name'] = $business;
            return  $CONFIGS;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}

?>