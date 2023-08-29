<?php

use Bizao\Bizao;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$bizao = new Bizao('MYAPP');

// print_r($bizao);

/** DEPOT-Burkina  */
// $deposit = $bizao->deposit('BF', 'ORANGE', ["phone" => 22657474578, "amount" => 100, "bash" => "D006", "otp" => 807035]);
// $deposit = $bizao->deposit('BF', 'MOOV', ["phone" => 22660565103, "amount" => 20000, "bash" => "D013"]);
// print_r($deposit ?? []);
/**
 * Array
(
    [code] => 200
    [data] => Array
        (
            [status] => InProgress
            [amount] => 20000
            [order-id] => D013
            [currency] => XOF
            [reference] => Barkalab
            [date] => 2023-08-24 11:05:16.0
            [country-code] => bf
            [state] => 22660565103%7CD013%7C20000
            [user_msisdn] => 22660565103
            [intTransaction-id] => 7901686d-bfb9-409c-a550-2c246302a8cc
            [extTransaction-id] => MROR230824.1104.C01970
            [statusDescription] => 
        )

)
 */


/** CHECKED */
// D004 | D011 | D012
// $check = $bizao->check('BF', 'MOOV', "D012");
// $check = $bizao->check('BF', 'ORANGE', "D001");
// print_r($check ?? []);
/**
 * Exemple de reponse checked
 * Array
(
    [code] => 200
    [data] => Array
        (
            [status] => Failed
            [amount] => 10
            [order-id] => D001
            [currency] => XOF
            [reference] => Barkalab
            [date] => 2023-08-23 12:23:45.0
            [country-code] => bf
            [state] => 57474578%7CD001%7C10
            [user_msisdn] => 57474578
            [intTransaction-id] => b2e97811-2e9d-40ea-9aae-2bd2c7a4ebf9
            [extTransaction-id] => OM230823.1223.U15123
            [statusDescription] => Trans ID: OM230823.1223.U15123. La transaction a echoue, saction a echoue, le montant que vous avez entre est insuffisant pour effectuer cette transaction. Veuillez reessayer.
        )

)
*/



/** REPORTING */
// $dateS = '2023-08-24';    //  par defaut heure minute est 00:00
// $dateE = '2023-08-24';    //  par defaut heure minute est 23:59
// $reporting = $bizao->reporting($dateS, $dateE);
// $reporting = $bizao->reporting($dateS, $dateE, 7, 2); // page 7, renvoyer 2 donnees par paginnation
// print_r($reporting ?? []);
/**
 * Exemple de reponse reporting
 * Array
(
    [code] => 200
    [data] => Array
        (
            [0] => stdClass Object
                (
                    [paymentToken] => 857e96d82b74e48b99fd97ba49935930a75171eecb64ebca4517120a9756c67
                    [country_code] => bf
                    [mno_name] => moov
                    [currency] => XOF
                    [order_id] => D012
                    [extTransaction-id] => MROR230824.1013.C01619
                    [intTransaction-id] => 316dea7d-669f-4a31-bebf-244ce5807e2b
                    [reference] => Barkalab
                    [date] => 2023-08-24 10:14:11
                    [amount] => 100
                    [channel] => tpe
                    [status] => Successful
                    [service_provider] => Barkalab
                    [user_msisdn] => 22660565103
                )

        )

    [page_num] => 1
    [page_size] => 25
    [record_count] => 25
    [total_record_count] => 1
    [total_page_count] => 1
    [next_page] => 
)
 */


/** MOBILES CALLBACK OPERATION STATUS 
 * $jsonString  = file_get_contents("php://input");  // Data receive from Bizao server
 * $dataArray   = json_decode($json, true);          // decode data
 * - Retourne un array comportant les informations de la transaction
 * - Retourne False si verification des donnees incorrects
*/
// $jsonString = '{"meta":{"type":"payment","source":"bf_moov_mm","channel":"tpe"},"status":"Successful","amount":"100","order-id":"D012","currency":"XOF","reference":"Barkalab","date":"2023-08-24 10:14:11.0","country-code":"bf","state":"22660565103%7CD012%7C100","user_msisdn":"22660565103","intTransaction-id":"316dea7d-669f-4a31-bebf-244ce5807e2b","extTransaction-id":"MROR230824.1013.C01619","statusDescription":""}'; // obtenue par file_get_contents("php://input");
// $jsonString = '{"meta":{"type":"payment","source":"bf_moov_mm","channel":"tpe"},"status":"Failed","amount":"20000","order-id":"D013","currency":"XOF","reference":"Barkalab","date":"2023-08-24 11:05:15.0","country-code":"bf","state":"22660565103%7CD013%7C20000","user_msisdn":"22660565103","intTransaction-id":"7901686d-bfb9-409c-a550-2c246302a8cc","extTransaction-id":"MROR230824.1104.C01970","statusDescription":""}';
// $dataArray = json_decode($jsonString, true);
// $status = $bizao->operationStatus($dataArray);
// print_r($status ?? []);
/**
 * Exemple de reponse 'Successful'
 * Array
(
    [status] => Successful
    [amount] => 100
    [order-id] => D012
    [currency] => XOF
    [reference] => Barkalab
    [date] => 2023-08-24 10:14:11.0
    [country-code] => bf
    [state] => 22660565103%7CD012%7C100
    [user_msisdn] => 22660565103
    [intTransaction-id] => 316dea7d-669f-4a31-bebf-244ce5807e2b
    [extTransaction-id] => MROR230824.1013.C01619
    [statusDescription] => 
)
 * 
 * Exemple de reponse 'Failed'
 * Array
(
    [status] => Failed
    [amount] => 20000
    [order-id] => D013
    [currency] => XOF
    [reference] => Barkalab
    [date] => 2023-08-24 11:05:16.0
    [country-code] => bf
    [state] => 22660565103%7CD013%7C20000
    [user_msisdn] => 22660565103
    [intTransaction-id] => 7901686d-bfb9-409c-a550-2c246302a8cc
    [extTransaction-id] => MROR230824.1104.C01970
    [statusDescription] => 
)
 */






 /** CARD PAYMENT */
$card = $bizao->card('BF', 100, 'C001');
print_r($card ?? []);

//  /** CARD CHECKED */
// $cardCheck = $bizao->cardCheck('CI', "order_id");
// print_r($cardCheck ?? []);



//  /** BULK TRANSFER */
// $withdraw = $bizao->withdraw('BF', 'ORANGE', ["phone" => 22657474578, "amount" => 100, "bash" => "D006"]);
// print_r($withdraw ?? []);

//  /** BULK CHECKED */
// $bulkCheck = $bizao->bulkCheck("batchNumber");
// print_r($bulkCheck ?? []);


// /** BULK BALANCE */
// $balance = $bizao->balance();
// print_r($balance ?? []);
/**
 * Exemple de reponse demande balance pour transfert 
 * Array
(
    [code] => 200
    [data] => Array
        (
            [status] => Active
            [currency] => XOF
            [balance] => 0
        )

)
 */




/**
 * Array
(
    [meta] => stdClass Object
        (
            [source] => bizao
            [merchantName] => Barkalab@carbon.super
            [type] => bulk
            [currency] => XOF
            [batchNumber] => W008
            [reference] => Barkalab
            [feesType] => HYBRID_FEE
            [lang] => fr
            [totalAmount] => 100
            [totalFees] => 0
            [senderFirstName] => Barka
            [senderLastName] => Change
            [senderAddress] => Burkina
            [senderMobileNumber] => 22676615699
            [fromCountry] => bf
            [comment] => Bulk Process will take minimum  8.23 minutes, Bulk items size is 1
        )

    [data] => Array
        (
            [0] => stdClass Object
                (
                    [id] => W008
                    [order_id] => W008W008
                    [mno] => orange
                    [date] => 2023-08-29T14:56:18.601
                    [beneficiaryFirstName] => Bizao
                    [beneficiaryLastName] => Hub
                    [beneficiaryAddress] => Rue 29 angle 20, Dakar
                    [beneficiaryMobileNumber] => 2250779772659
                    [toCountry] => ci
                    [feesApplicable] => No
                    [amount] => 100
                    [fees] => 0
                    [status] => Pending
                    [statusDescription] => 
                    [intTransaction-Id] => f3c9fc68-aede-4712-8113-f49543b58d23
                    [extTransaction-Id] => 
                    [reason] => 
                )

        )

)

 */

