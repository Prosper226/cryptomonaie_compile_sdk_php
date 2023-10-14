<?php

use MoovAfrica\Moov;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$moov  = new Moov('MYAPP');

// print_r($moov->checkSubscriber('22660565103'));   

// print_r($moov->payment(22660565103, 100)); 
// print_r($moov->transfert(22660565103, 100)); 

?>