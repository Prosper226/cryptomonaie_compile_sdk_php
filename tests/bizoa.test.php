<?php

use Bizao\Bizao;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$bizao = new Bizao('MYAPP');

// print_r($bizao);   // Voir l'objet BIZAO 


////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////

/** EXEMPLE DEPOT  */
// $deposit = $bizao->deposit('BF', 'ORANGE', ["phone" => 22657474578, "amount" => 100, "bash" => "D006", "otp" => 807035]);
// $deposit = $bizao->deposit('BF', 'MOOV', ["phone" => 22660565103, "amount" => 20000, "bash" => "D013"]);
// print_r($deposit ?? []);
/**
 * Exemple de reponse
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





/** EXEMPLE CHECKED DEPOT */
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





/** EXEMPLE DE REPORTING */
// $dateS = '2023-08-24';    //  par defaut heure minute est 00:00 pour la date de depart (Start)
// $dateE = '2023-08-24';    //  par defaut heure minute est 23:59 pour la date d'arrivee (End)
// $reporting = $bizao->reporting($dateS, $dateE);
// $reporting = $bizao->reporting($dateS, $dateE, 7, 2);  // page 7, renvoyer 2 donnees par pagination
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



////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////

/** BULK BALANCE */
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
            [balance] => 4300
        )

)
 */





/** BULK CHECKED */
// W008 , W002
// $bulkCheck = $bizao->bulkCheck("W003");
// print_r($bulkCheck ?? []);
/**
 * Exemple de reponse
 * Array
(
    [code] => 200
    [data] => Array
        (
            [batchNumber] => W003
            [order_id] => W003
            [mno] => orange
            [date] => 2023-08-29T17:04:09
            [beneficiaryMobileNumber] => 2250779772659
            [toCountry] => ci
            [feesApplicable] => Yes
            [amount] => 500
            [fees] => 200
            [status] => Successful
            [currency] => XOF
        )

)
*/





/** BULK TRANSFER */
// Les transferts sont actifs uniquement pour CI, SN, CM
// $withdraw = $bizao->withdraw('BF', 'MOOV', ["phone" => 22660565103, "amount" => 100, "bash" => "W007"]);
// $withdraw = $bizao->withdraw('CI', 'ORANGE', ["phone" => 2250779772659, "amount" => 500, "bash" => "W003"]);
// print_r($withdraw ?? []);
/**
 * Exemple de reponse
 * Array
(
    [code] => 200
    [data] => Array
        (
            [batchNumber] => W004
            [order_id] => W004
            [mno] => orange
            [date] => 2023-08-30T16:23:25.055
            [beneficiaryMobileNumber] => 2250779772659
            [toCountry] => ci
            [feesApplicable] => Yes
            [amount] => 100
            [fees] => 200
            [status] => Pending
            [currency] => XOF
        )

)
 */



////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////

/** CARD PAYMENT */
// $card = $bizao->card(200, 'C004');
// print_r($card ?? []);
/**
 * Exemple de reponse
 * Array
(
    [code] => 200
    [data] => Array
        (
            [status] => 201
            [message] => OK
            [payment_token] => 33715e2e-c9a3-48b8-a783-fa483452a334
            [order_id] => C003
            [state] => C003%7C200
            [payment_url] => https://visamc.bizao.com/visa-mc/33715e2e-c9a3-48b8-a783-fa483452a334
        )

)
 */





/** CARD CHECKED */
// order_id , C002, C003, C004
// $cardCheck = $bizao->cardCheck("C003");
// print_r($cardCheck ?? []);
/**
 * Exemple de reponse
 * Arrays
(
    [code] => 200
    [data] => Array
        (
            [status] => InProgress
            [amount] => 100.00
            [order-id] => C002
            [currency] => XOF
            [reference] => Barkalab
            [date] => 2023-08-30 15:18:39.0
            [country-code] => ci
            [state] => C002%7C100
            [intTransaction-id] => 1ad2e892-b0ba-44dc-91f1-aa17dbc9b082
            [extTransaction-id] => 
            [statusDescription] => 
        )

)
 */



////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////

/** CALLBACK OPERATION STATUS PAYMENT | TRANSFER | CARD
 * var $jsonString  = file_get_contents("php://input");                 // Donnees recuperees par le callback de bizao
 * var $dataArray   = json_decode($json, true);                         // decodage des donnees en Tableau
 * - Retourne un array comportant les informations de la transaction
 * - Retourne False si verification des donnees incorrects
*/

/** Exemple de donnees recues des callback bizao*/
// - Payment success sample
// $jsonString = '{"meta":{"type":"payment","source":"bf_moov_mm","channel":"tpe"},"status":"Successful","amount":"100","order-id":"D012","currency":"XOF","reference":"Barkalab","date":"2023-08-24 10:14:11.0","country-code":"bf","state":"22660565103%7CD012%7C100","user_msisdn":"22660565103","intTransaction-id":"316dea7d-669f-4a31-bebf-244ce5807e2b","extTransaction-id":"MROR230824.1013.C01619","statusDescription":""}'; // obtenue par file_get_contents("php://input");
// - Payment failed sample
// $jsonString = '{"meta":{"type":"payment","source":"bf_moov_mm","channel":"tpe"},"status":"Failed","amount":"20000","order-id":"D013","currency":"XOF","reference":"Barkalab","date":"2023-08-24 11:05:15.0","country-code":"bf","state":"22660565103%7CD013%7C20000","user_msisdn":"22660565103","intTransaction-id":"7901686d-bfb9-409c-a550-2c246302a8cc","extTransaction-id":"MROR230824.1104.C01970","statusDescription":""}';
// - Bulk failed sample
// $jsonString = '{"meta":{"source":"bizao","merchantName":"Barkalab@carbon.super","type":"bulk","currency":"XOF","batchNumber":"W009","reference":"Barkalab","feesType":"HYBRID_FEE","lang":"fr","totalAmount":100,"totalFees":0,"senderFirstName":"Barka","senderLastName":"Change","senderAddress":"Burkina","senderMobileNumber":"22676615699","fromCountry":"bf","date":"2023-08-29T14:56:18"},"data":[{"id":"W008","order_id":"W008W008","mno":"orange","date":"2023-08-29T15:11:26","beneficiaryFirstName":"Bizao","beneficiaryLastName":"Hub","beneficiaryAddress":"Rue 29 angle 20, Dakar","beneficiaryMobileNumber":"2250779772659","toCountry":"ci","feesApplicable":"No","amount":100,"fees":0,"status":"Failed","statusDescription":"USSD transaction failed [cause-Err-00E0  pdu_cnt-6  pdu_seq-2","failedReason":"USSD transaction failed [cause-Err-00E0  pdu_cnt-6  pdu_seq-2","intTransaction-Id":"f3c9fc68-aede-4712-8113-f49543b58d23","extTransaction-Id":"","reason":null}]}';
// $jsonString = '{"meta":{"source":"bizao","merchantName":"Barkalab@carbon.super","type":"bulk","currency":"XOF","batchNumber":"W002","reference":"Barkalab","feesType":"HYBRID_FEE","lang":"fr","totalAmount":100,"totalFees":200,"senderFirstName":"Barka","senderLastName":"Change","senderAddress":"Burkina","senderMobileNumber":"22676615699","fromCountry":"bf","date":"2023-08-29T16:36:10"},"data":[{"id":"W002","order_id":"W002W002","mno":"orange","date":"2023-08-29T16:40:01","beneficiaryFirstName":"Bizao","beneficiaryLastName":"Hub","beneficiaryAddress":"Rue 29 angle 20, Dakar","beneficiaryMobileNumber":"2250779772659","toCountry":"ci","feesApplicable":"Yes","amount":100,"fees":200,"status":"Failed","statusDescription":"montant saisi ne se trouve dans aucune tranche","failedReason":"montant saisi ne se trouve dans aucune tranche","intTransaction-Id":"e174da16-027d-49ce-98e3-f7f65a7a53c0","extTransaction-Id":"","reason":null}]}';
// - Bulk success sample
// $jsonString = '{"meta":{"source":"bizao","merchantName":"Barkalab@carbon.super","type":"bulk","currency":"XOF","batchNumber":"W003","reference":"Barkalab","feesType":"HYBRID_FEE","lang":"fr","totalAmount":500,"totalFees":200,"senderFirstName":"Barka","senderLastName":"Change","senderAddress":"Burkina","senderMobileNumber":"22676615699","fromCountry":"bf","date":"2023-08-29T17:00:21"},"data":[{"id":"W003","order_id":"W003W003","mno":"orange","date":"2023-08-29T17:04:09","beneficiaryFirstName":"Bizao","beneficiaryLastName":"Hub","beneficiaryAddress":"Rue 29 angle 20, Dakar","beneficiaryMobileNumber":"2250779772659","toCountry":"ci","feesApplicable":"Yes","amount":500,"fees":200,"status":"Successful","statusDescription":"Transaction succeed at MNO","intTransaction-Id":"024beeea-1d83-4bce-9aff-990cd11c309d","extTransaction-Id":"","reason":null}]}';
// - Card success sample
// $jsonString = '{"meta":{"type":"payment","source":"ci_Visa","category":"BIZAO"},"status":"Successful","amount":"100.00","order-id":"C002","currency":"XOF","reference":"Barkalab","date":"2023-08-30 15:18:39","country-code":"ci","state":"C002%7C100","intTransaction-id":"1ad2e892-b0ba-44dc-91f1-aa17dbc9b082","extTransaction-id":"6934091907776406104225","statusDescription":""}';

/** Execution des functions */
// $dataArray = json_decode($jsonString, true);
// $status = $bizao->operationStatus($dataArray);
// print_r($status ?? []);

/**
 * Exemple de reponse payment
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
 */

/**
 * Exemple reponse transfer
 * Array
(
    [batchNumber] => W008
    [order_id] => W008
    [mno] => orange
    [date] => 2023-08-29T15:11:26
    [beneficiaryMobileNumber] => 2250779772659
    [toCountry] => ci
    [feesApplicable] => No
    [amount] => 100
    [fees] => 0
    [status] => Failed
)
 */

/**
 * Exemple de reponse card
 * Array
(
    [status] => Successful
    [amount] => 100.00
    [order-id] => C002
    [currency] => XOF
    [reference] => Barkalab
    [date] => 2023-08-30 15:18:39.0
    [country-code] => ci
    [state] => C002%7C100
    [intTransaction-id] => 1ad2e892-b0ba-44dc-91f1-aa17dbc9b082
    [extTransaction-id] => 6934091907776406104225
    [statusDescription] => 
)
 */
