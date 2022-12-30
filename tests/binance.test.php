<?php

use Binance\Binance;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$binance = new Binance('MYAPP');

// print_r($binance->mstime());
// print_r($binance->all_coins_information());
// print_r($binance->system_status());
// print_r($binance->networkList());



// $binance->new_order($symbol, "BUY",$roundedPrice);
// $binance->withdraw($get, $address, ($totalToSend), $network, null,$txid);
// $binance->new_order($symbol, "SELL",$tochangeAmount);


// print_r($binance->account_information('btc'));                   // ok 
// print_r($binance->found_txid($txid, $startTime, 'deposit'));     // ok
// print_r($binance->global_spot_balance());

// print_r($binance->get_symbol_ticker('BTCUSDT'));
// print_r($binance->get_symbol_ticker('USDTBTC'));



?>