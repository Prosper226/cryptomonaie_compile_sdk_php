<?php

use Nowpayment\Nowpayment;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$nowpayment = new Nowpayment('MYAPP');

// print_r($nowpayment->system_status()); // ok

// print_r($nowpayment->currencies()); 

// print_r($nowpayment->estimetedPrice(3999.500 , 'usd', 'btc'));

print_r($nowpayment->minPaymentAmount());



?>