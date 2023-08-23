<?php

use Intouch\Intouch;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

// $intouch = new Intouch('MYCLIENT', 'BURKINA');
// $intouch = new Intouch('MYCLIENT', 'COTEIVOIRE');
$intouch = new Intouch('MYCLIENT', 'GABON');

// print_r($intouch);
print_r($intouch->balance());
// print_r($intouch->status());
// print_r($intouch->withdraw('ORANGE', ["phonenumber" => 57474578, "amount" => 100, "txId" => "TEST-RETRAIT-230304-001"]));
// print_r($intouch->withdraw('MOOV', ["phonenumber" => 72287800, "amount" => 100, "txId" => "TEST-RETRAIT-230304-002"]));
// print_r($intouch->deposit('AIRTEL', ["phonenumber" => 074772549, "amount" => 100, "txId" => "TEST-DEPOT-230304-007"]));
// print_r($intouch->deposit('MOOV', ["phonenumber" => 72287800, "amount" => 500, "txId" => "TEST-DEPOT-230304-009"]));

// print_r($intouch->deposit('MOOV', ["phonenumber" => '060011896', "amount" => 105, "txId" => "TEST-DEPOT-230407-003"]));
// print_r($intouch->withdraw('MOOV', ["phonenumber" => '060011896', "amount" => 100, "txId" => "TEST-RETRAIT-230407-004"]));

// print_r($intouch->deposit('AIRTEL', ["phonenumber" => '074772549', "amount" => 100, "txId" => "TEST-DEPOT-230407-001"]));
// print_r($intouch->withdraw('AIRTEL', ["phonenumber" => '074772549', "amount" => 100, "txId" => "TEST-RETRAIT-230407-002"]));



?>