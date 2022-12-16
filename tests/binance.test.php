<?php

use Binance\Binance;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$binance = new Binance('MYAPP');

// print_r($binance->mstime());

// print_r($binance->all_coins_information());

// print_r($binance->system_status());

// print_r($binance->networkList());

?>