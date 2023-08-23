<?php

use Binance\Binance;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$binance = new Binance('MYAPP');

// // print_r($binance->mstime());
// // print_r($binance->all_coins_information());
// // print_r($binance->system_status());
// // print_r($binance->networkList());

// $binance->new_order($symbol, "BUY",$roundedPrice);                           // ok
// $binance->withdraw($get, $address, ($totalToSend), $network, null, $txid);   // ok
// $binance->new_order($symbol, "SELL",$tochangeAmount);                        // ok
// print_r($binance->account_information('btc'));                                  // ok  // get_asset_balance
// print_r($binance->found_txid($txid, $startTime, 'deposit'));                 // ok
// print_r($binance->global_spot_balance());                                    // ok

// // print_r($binance->get_symbol_ticker('TRXUSDT'));
// // print_r($binance->get_symbol_ticker('USDTBTC'));

// trouver une op selon le id de binance
// print_r($binance->found_txid('0737da6abca1d25540e41d9d2b3b161807cf78631a3ca3dbb0be0d8ea6ecf7e9', 1666348534000, 'withdraw'));
// print_r($binance->found_txid('393a15a8caddaa6326dff004847d4b86d3667ec958e6abbb24874868adeda137', 1671613713000, 'deposit'));

?>