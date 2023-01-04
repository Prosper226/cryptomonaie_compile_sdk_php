<?php

namespace MoovAfrica;

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
        $this->app      =   new App('moovafrica');
        $this->config   =   $this->app->getAccess($this->business);
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Moovafrica');
    }

    
    public function checkSubscriber($phone = null){
        try{
            if(!isset($phone) || !$phone) throw new Exception('phonenumber param is mandatory.');
            $command = url_recode($this->config['command-id']['payment']);
            $body = array ('destination' => $phone, "request-id" => "check_$phone".time());
            $result = $this->request->make('POST', $body, $command);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}

?>
