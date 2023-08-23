<?php

use Crypto\Crypto;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$Cryptosys = new Crypto('MYAPP');

// $status = $Cryptosys->status();
// // $status = $Cryptosys->status();
// print_r($status);

// $withdraw = $Cryptosys->withdraw('BTC', ["amount" => 0.001, "bash" => "WM271022_4", "address" => "1EuVkAWJ5gJfT8yyoUkiBPwS85CZh91UJq"]);
// print_r($withdraw);

$deposit = $Cryptosys->deposit('BTC', ["amount" => 300, "bash" => "DM161122_11"]);
// $deposit1 = $Cryptosys->deposit('BTC', ["amount" => 300, "bash" => "DM161122_6"]);
// $deposit2 = $Cryptosys->deposit('BTC', ["amount" => 300, "bash" => "DM161122_7"]);
// $deposit = (array)$deposit;
print_r($deposit); 

// $cancel = $Cryptosys->cancel("DM271022_4");
// print_r($cancel);

// $check = $Cryptosys->check("DM271022_3");
// print_r($check);

// $history = $Cryptosys->history(["type" => 'deposit', "startTimestamp" => 1658243219, "endTimestamp" => 1662460620]);
// $history = $Cryptosys->history(["type" => 'withdraw', "startTimestamp" => 1658243219, "endTimestamp" => 1662460620]);
// print_r($history->data[0]->phone);
// print_r($history);

// $callbackReceive = $Cryptosys->callbackReceive("DM271022_4");
// print_r($callbackReceive);

// $balance = $Cryptosys->balance('ETH');
// print_r($balance);

?>