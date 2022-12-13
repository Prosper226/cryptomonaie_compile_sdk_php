<?php

namespace Kraken;

use Exception;
use lab\App;
use lab\Request;
use stdClass;

use function lab\url_recode;

class Manager{

    private $app        = null;
    private $config     = null;
    private $business   = null;
    private $request    = null;
    
    public function __construct($business){
        try{
            $this->business =   $business;
            $this->app      =   new App('kraken');
            $this->config   =   $this->app->getAccess($this->business);
            $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Kraken');
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    public function getBalance(){
        try{
            $url = url_recode($this->config['endpoint']['balance']);
            $res = $this->request->make('POST', [], $url);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}
?>