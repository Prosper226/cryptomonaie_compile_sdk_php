<?php
    use Kraken\KrakenAPI;
    use Kraken\Kraken;
    require(__DIR__.'/vendor/autoload.php');
    // echo '<pre>';
    // KRAKEN
    $kraken  = new Kraken('MYAPP');
    $balance = $kraken->getBalance();
    print_r($balance);


        // your api credentials
    // $key    = 'G5/BbR6qEZaJZkWAx1vSt1yQE9F0+c0U4fmhWLXXWn3NJxTIZLPX3pJJ';
    // $secret = 'cUWhthKrvfaDNllKhToWA4d3krWe2PalsAon15Zq6ONRtrIGGpqOfeJ/SR8KJ1CWo+JTWcHEkIQOimRtmbfNiA==';

    // // set which platform to use (currently only beta is operational, live available soon)
    // $beta = false; 
    // $url = $beta ? 'https://api.beta.kraken.com' : 'https://api.kraken.com';
    // $sslverify = $beta ? false : true;
    // $version = 0;

    // $kraken = new KrakenAPI($key, $secret, $url, $version, $sslverify);

    // // Query a public list of active assets and their properties: 
    // // $res = $kraken->QueryPublic('Assets');
    // // print_r($res);

    // $res = $kraken->QueryPrivate('Balance');
    // print_r($res);


    // $nonce      = microtime();
    // $nonceExpl  = explode(' ', $nonce);
    // $substr     = substr($nonceExpl[0], 2, 6);
    // $str_pad    = str_pad($substr, 6, '0', STR_PAD_RIGHT);
    // $explain = [
    //     "microtime" => $nonce,
    //     "nonce"     => $nonceExpl,
    //     "substr"    => $substr,
    //     "str_pad"   => $str_pad,
    //     "final"     => $nonceExpl[1].$str_pad,
    //     "LEN"   => [strlen($nonceExpl[1]), strlen($nonceExpl[1].$str_pad)]
    // ];
    // print_r($explain);

?>