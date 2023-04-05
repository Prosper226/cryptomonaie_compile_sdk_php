<?php

use Bapi\Bapi;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$bapi = new Bapi('MYAPP2');

// print_r($bapi);

/** balances */
// print_r($bapi->balance());
// print_r($bapi->balance('ORANGE'));

/** deposits */
print_r($bapi->deposit('ORANGE', ["phone" => 22676615699, "amount" => 100, "bash" => "depot_230310_002"]));

/** withdraw */
// print_r($bapi->withdraw('ORANGE', ["phone" => 22657474578, "amount" => 10, "bash" => "test_retrait_100301"]));

/** canceled  */
// print_r($bapi->cancel("test_d_001"));

/** checked */
// print_r($bapi->check("PAY-7185-633376-3276"));

// print_r($bapi->askCallback('DM271022_1'));

/** historique transactions */
// print_r($bapi->history(["type" => 'deposit', "startTimestamp" => 1658243219, "endTimestamp" => 1676282460]));
// print_r($bapi->history(["type" => 'withdraw', "startTimestamp" => 1658243219, "endTimestamp" => 1676282460]));

/** historique sms */
// print_r($bapi->smsHistory());

/** historique restitution */
// print_r($bapi->restitutes(["startTimestamp" => 1662036594, "endTimestamp" => 1676282460]));

/** historique zombie */
// print_r($bapi->zombiesHistory(["startTimestamp" => 1662036594, "endTimestamp" => 1676282460]));

/** callback confirm */
// print_r($bapi->callbackReceive('PMU-7181-772422-2161'));

/** merchant payout */
// print_r($bapi->payClub('MOOV', ["phone" => 22657474578, "amount" => 500, "bash" => "WM171022_5"]));

/** server status */
// print_r($bapi->status());
// print_r($bapi->status('ORANGE'));


// $status = $Bapi->OperationStatus($data);



// print_r($bapi->restoreSms('ORANGE', 1678448460, 1678448793));
// print_r($bapi->batteryLevel('ORANGE'));


?>