<?php

use CinetPay\Cinetpay;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$cinetpay = new Cinetpay("MYAPP");

// print_r($cinetpay);

// print_r($cinetpay->deposit("BFA",["phone" => 57474578, "amount" => 250, "bash" => "test_depot_2303_001"]));
// print_r($cinetpay->withdraw("BFA",["phone" => 57474578, "amount" => 250, "bash" => "test_retrait_2303_001"]));
// print_r($cinetpay->balance());


// stdClass Object
// (
//     [code] => 0
//     [message] => OPERATION_SUCCES
//     [data] => Array
//         (
//             [0] => Array
//                 (
//                     [0] => stdClass Object
//                         (
//                             [prefix] => 226
//                             [phone] => 57474578
//                             [amount] => 500
//                             [notify_url] => https://www.messenger.brklb.space/cinetpay/notify
//                             [client_transaction_id] => Test_retrait_20230301_01
//                             [code] => 0
//                             [status] => success
//                             [treatment_status] => NEW
//                             [transaction_id] => EA230301.165358.X622533
//                             [lot] => 001709482344731677689638
//                         )

//                 )

//         )

// )


// [post] => Array
//         (
//             [cpm_site_id] => 550781
//             [cpm_trans_id] => Test_depot_2302_002
//             [cpm_trans_date] => 2023-03-02 11:16:45
//             [cpm_amount] => 250
//             [cpm_currency] => XOF
//             [signature] => f4362b8959a5ebf9eafeff228862636fe239ab3ac0d815a3197aa90be00e7b13149539
//             [payment_method] => OMBF
//             [cel_phone_num] => 57474578
//             [cpm_phone_prefixe] => 226
//             [cpm_language] => fr
//             [cpm_version] => V4
//             [cpm_payment_config] => SINGLE
//             [cpm_page_action] => PAYMENT
//             [cpm_designation] => Paiement mobile vers MYAPP
//         )


?>