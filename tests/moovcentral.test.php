<?php

use MoovCentral\Moov ;
require(dirname(__DIR__, 1).'/vendor/autoload.php');

$moov  = new Moov('MYAPP');

// print_r($moov->getBalance());   
// print_r($moov->payment(60565103, 500, 'prosTest28')); 
// print_r($moov->transfert(60565103, 500, 'prosTest30')); 
print_r($moov->getHistory('depot')); 
// print_r($moov->getHistory('retrait')); 

?>