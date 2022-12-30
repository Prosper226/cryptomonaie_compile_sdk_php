<?php

use Nowpayment\Nowpayment;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$nowpayment = new Nowpayment('MYAPP2');

// print_r($nowpayment->system_status()); // ok

// print_r($nowpayment->currencies()); 

// print_r($nowpayment->estimetedPrice(3999.500 , 'usd', 'btc'));

// print_r($nowpayment->minPaymentAmount('eth', 'eth'));

// print_r($nowpayment->createPayment(["price_amount" => 5, "price_currency" => 'trx', "pay_currency" => 'trx', "order_id" => "1234_02"]));

// print_r($nowpayment->paymentStatus('6168839835')); 

// print_r($nowpayment->balance());

// print_r($nowpayment->createPayout(["address" => 'TG2jmPc38wBdUz9ufvw1HdVq9X9LBuKs5V', "currency" => 'trx', "amount" => 90]))

// print_r($nowpayment->createInvoicePayment(["price_amount" => 10, "price_currency" => 'usd', "pay_currency" => 'trx', "order_id" => "1234_100"]));

// print_r($nowpayment->createInvoice(["price_amount" => 10, "price_currency" => 'usd', "pay_currency" => 'trx', "order_id" => "1234_09"]));


?>