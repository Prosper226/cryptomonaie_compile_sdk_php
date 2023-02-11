<?php

use Bapi\Bapi;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$bapi = new Bapi('MYAPP');

// print_r($bapi);

// print_r($bapi->balance('MOOV'));
// print_r($bapi->deposit('ORANGE', ["phone" => 22657474578, "amount" => 500, "bash" => "test_depot_003"]));
// print_r($bapi->withdraw('ORANGE', ["phone" => 22657474578, "amount" => 100, "bash" => "test_retrait_001"]));
// print_r($bapi->cancel("test_depot_003"));
// print_r($bapi->check("test_depot_003"));


?>