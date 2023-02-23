<?php

use Ligdicash\Ligdicash;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$ligdicash  = new Ligdicash('MYAPP');

$payment = $ligdicash->payment(['phone' => 22657474578, 'amount' => 100, 'bash' => '202306I008', 'otp' => 393558 ]);
// $payment = $ligdicash->payment([ 'bash' => '202306I009', 'phone' => 22677456266, 'amount' => 100, 'otp' => 379900 ]);
// $transaction_id,$customer,$amount,$otp=''
header('Content-Type: application/json');
echo json_encode($payment);
// print_r($payment);
// $token1 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTM3MTc0MjAiLCJzdGFydF9kYXRlIjoiMjAyMy0wMS0wNiAxNTowNTo1NyIsImV4cGlyeV9kYXRlIjoxNjczMTAwMzU3fQ.Nv1cj-OnMjXAr-UNlu96hagdPB_9hKlyfYp4fsa-oA8";

// $payment = $ligdicash->payment(['phone' => 22657474578, 'amount' => 500, 'bash' => '202306I002', 'otp' => 727445 ]);
// print_r($payment);
// $token2 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTM3MTg3MTYiLCJzdGFydF9kYXRlIjoiMjAyMy0wMS0wNiAxNToxNjozNiIsImV4cGlyeV9kYXRlIjoxNjczMTAwOTk2fQ.Sar8XMT2ht64V-bxOX2H-p_j9vfFU8mWZfwrwNHehIc";


// $payment = $ligdicash->payment(['phone' => 22660565103, 'amount' => 500, 'bash' => '202306I002' ]);
// print_r($payment);

// $token3 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF9pbnZvaWNlIjoiMTM3MjI0OTYiLCJzdGFydF9kYXRlIjoiMjAyMy0wMS0wNiAxNTo1MToxOCIsImV4cGlyeV9kYXRlIjoxNjczMTAzMDc4fQ.tFOxhkzq7wohS7al-zEzXWWjVXeIRXXuAQPvoCe3laY";
// $statusPayment = $ligdicash->paymentStatus($token3);
// print_r($statusPayment); 


// {
//     "commande":{
//         "invoice":
//         {
//             "items":[
//                 {
//                     "name":"Nom du produit ou Service",
//                     "description":" Description du produit ou Service ",
//                     "quantity":1,
//                     "unit_price":"100",
//                     "total_price":"100"
//                 }
//             ],
//             "total_amount":"100",
//             "devise":"XOF",
//             "description":" Description du contenu de la facture(Achat de jus de fruits)"
//             ,"customer":"22657474578",
//             "customer_firstname":"Nom du client",
//             "customer_lastname":"Prenon du client",
//             "customer_email":"email du client exemple tester@gligdicash.com",
//             "external_id":"",
//             "otp":"393558"
//         },
//         "store":{
//             "name":"Nom de votre site ou de votre boutique",
//             "website_url":"url de votre site ou de votre boutique"
//         },
//         "actions":{
//             "cancel_url":"https:\/\/www.pmuflash-dev.brklb.space\/sample_ligdicash",
//             "return_url":"https:\/\/www.pmuflash-dev.brklb.space\/sample_ligdicash",
//             "callback_url":"https:\/\/www.pmuflash-dev.brklb.space\/sample_ligdicash"
//         },
//         "custom_data":{
//             "transaction_id":"202306I008"
//         }
//     }
// }

// {
//     "commande":{
//         "invoice":{
//             "items":[
//                 {"
//                     name":"Nom du produit ou Service",
//                     "description":" Description du produit ou Service ",
//                     "quantity":1,
//                     "unit_price":"100",
//                     "total_price":"100"
//                 }
//             ],
//             "total_amount":"100",
//             "devise":"XOF",
//             "description":" Description du contenu de la facture(Achat de jus de fruits)",
//             "customer":"22657474578",
//             "customer_firstname":"Nom du client",
//             "customer_lastname":"Prenon du client",
//             "customer_email":"tester@gligdicash.com",
//             "external_id":"",
//             "otp":"393558"
//         },
//         "store":{
//             "name":"Nom de votre site ou de votre boutique",
//             "website_url":"https:\/\/www.pmuflash.com"
//         },
//         "actions":{
//             "cancel_url":"https:\/\/www.pmuflash-dev.brklb.space\/sample_ligdicash",
//             "return_url":"https:\/\/www.pmuflash-dev.brklb.space\/sample_ligdicash",
//             "callback_url":"https:\/\/www.pmuflash-dev.brklb.space\/sample_ligdicash"
//         },
//         "custom_data":{
//             "transaction_id":"202306I008"
//         }
//     }
// }
?>