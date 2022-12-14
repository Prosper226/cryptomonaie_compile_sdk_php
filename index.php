<?php
    require(__DIR__.'/vendor/autoload.php');
    $nonce      = microtime();
    $nonceExpl  = explode(' ', $nonce);
    $substr     = substr($nonceExpl[0], 2, 6);
    $str_pad    = str_pad($substr, 6, '0', STR_PAD_RIGHT);
    $explain = [
        "microtime" => $nonce,
        "nonce"     => $nonceExpl,
        "substr"    => $substr,
        "str_pad"   => $str_pad,
        "final"     => $nonceExpl[1].$str_pad,
        "LEN"   => [strlen($nonceExpl[1]), strlen($nonceExpl[1].$str_pad)]
    ];
    print_r($explain);
?>