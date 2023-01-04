<?php

use Coinbase\Coinbase;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$coinbase = new Coinbase('MYAPP');

$user = $coinbase->show_current_user();
print_r($user);

// $accounts = $coinbase->list_accounts();
// print_r($accounts);

// $id = $coinbase->get_account_id('ada');
// print_r($id);

// $address = $coinbase->create_address('xrp');
// print_r($address);

// $account =  $coinbase->show_an_account('btc');
// print_r($account);

// $list_addresses = $coinbase->list_addresses('btc');
// print_r($list_addresses['data'][0]);

// $list_transactions = $coinbase->list_transactions('btc');
// print_r($list_transactions['data'][0]);



?>