<?php

namespace Nowpayment;

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
        $this->app      =   new App('nowpayment');
        $this->config   =   $this->app->getAccess($this->business);
        $this->request  =   new Request($this->config['baseUrl'], $this->config['APP']['api'], 'Nowpayment');
    }

    public function system_status(){
        try{
            $url = url_recode($this->config['endpoint']['apiStatus']);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function availableCurrencies(){
        try{
            $url = url_recode($this->config['endpoint']['availableCurrencies']);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function estimetedPrice($amount ,$currency_from, $currency_to){
        try{
            if(!isset($amount ,$currency_from, $currency_to)) {throw new Exception("invalid parameters entry");}
            $url = url_recode($this->config['endpoint']['estimetedPrice'])."?amount=$amount&currency_from=$currency_from&currency_to=$currency_to";
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function minPaymentAmount($from, $to){
        try{
            if(!isset($from ,$to)) {throw new Exception("invalid parameters entry");}
            $url = url_recode($this->config['endpoint']['minPaymentAmount'])."?currency_from=$from&currency_to=$to";
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function createPayment($data = ["price_amount" => null, "price_currency" => null, "pay_currency" => null, "order_id" => null]){
        try{
            $body = [
                "price_amount"      =>  $data['price_amount'],
                "price_currency"    =>  $data['price_currency'],
                "pay_currency"      =>  $data['pay_currency'],
                "order_id"          =>  $data["order_id"]
            ];
            if(isset($this->config['APP']['api']['ipn_callback_url']) && $this->config['APP']['api']['ipn_callback_url']){
                $body['ipn_callback_url'] = $this->config['APP']['api']['ipn_callback_url'];
            }
            $url = url_recode($this->config['endpoint']['createPayment']);
            $response    =  $this->request->make('POST', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function createInvoicePayment($data = ["price_amount" => null, "price_currency" => null, "pay_currency" => null, "order_id" => null]){
        try{
            $invoice = $this->createInvoice($data);
            // if($invoice['code'] != 200){ throw new Exception($invoice['error']);}
            // return $invoice;
            $invoice = (array) $invoice;
            return $invoice['code'];

            // $body = [
            //     "iid"                   => $invoice["id"],
            //     "pay_currency"          => $data['pay_currency'],
            //     // "payout_address"        => "0x...",
            //     // "payout_extra_id"       => null,
            //     // "payout_currency"       => "usdttrc20",
            // ];
            // /**
            //  * ipn_callback_url est refusé 
            //  */
            // $url = url_recode($this->config['endpoint']['createInvoicePayment']);
            // $response    =  $this->request->make('POST', $body, $url);
            // return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function createInvoice($data = ["price_amount" => null, "price_currency" => null, "pay_currency" => null, "order_id" => null]){
        try{
            $ipn_callback_url = $this->config['APP']['api']['ipn_callback_url'];
            $body = [
                "price_amount"      =>  $data['price_amount'],
                "price_currency"    =>  $data['price_currency'],
                "pay_currency"      =>  $data['pay_currency'],
                "order_id"          =>  $data["order_id"],
                "ipn_callback_url"  =>  $ipn_callback_url,
                "success_url"       =>  $ipn_callback_url.'_success',
                "cancel_url"        =>  $ipn_callback_url.'_cancel'
            ];
            $url = url_recode($this->config['endpoint']['createInvoice']);
            $response    =  $this->request->make('POST', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    public function paymentStatus($payment_id){
        try{
            $url = url_recode($this->config['endpoint']['paymentStatus'], [$payment_id]);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function balance(){
        try{
            $url = url_recode($this->config['endpoint']['balance']);
            $response    =  $this->request->make('GET', [], $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function createPayout($data = ["address" => null, "currency" => null, "amount" => null]){
        try{
            $body = [
                "withdrawals" => [
                    "address"   => $data['address'],
                    "currency"  => $data['currency'],
                    "amount"    => $data['amount'],
                ]
            ];
            $url = url_recode($this->config['endpoint']['createPayout']);
            $response    =  $this->request->make('POST', $body, $url);
            return $response;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}