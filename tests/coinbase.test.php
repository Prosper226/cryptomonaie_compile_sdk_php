<?php

use Coinbase\Coinbase;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$coinbase = new Coinbase('MYAPP2');

$user = $coinbase->show_current_user();
print_r($user);

// $accounts = $coinbase->list_accounts();
// print_r($accounts);

// $id = $coinbase->get_account_id('ada');
// print_r($id);

// $address = $coinbase->create_address('btc');
// print_r($address);

// $account =  $coinbase->show_an_account('btc');
// print_r($account);

// $list_addresses = $coinbase->list_addresses('btc');
// print_r($list_addresses['data'][0]);

// $list_transactions = $coinbase->list_transactions('btc');
// print_r($list_transactions['data'][0]);

// $check = $coinbase->check_transaction();
// print_r($check);

// case 'BEP2'     :   $final_network   =   'BNB';break;
// case 'BEP20'    :   $final_network   =   'BSC';break;
// case 'TRC20'    :   $final_network   =   'TRX';break;
// case 'ERC20'    :   $final_network   =   'ETH';break;

// $finder = $coinbase->findById('btc', 'my_withdraw_transaction_id);
// print_r($finder);

?>