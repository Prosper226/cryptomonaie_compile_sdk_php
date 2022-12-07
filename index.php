<?php

    use Coinbase\Coinbase;
    require(__DIR__.'/vendor/autoload.php');

    $coinbase = new Coinbase('MYAPP');

    echo '<pre>';

    // $user = $coinbase->show_current_user();
    // print_r($user);

    // $accounts = $coinbase->list_accounts();
    // print_r($accounts);

    // $id = $coinbase->get_account_id('ada');
    // print_r($id);

    // $address = $coinbase->create_address('btc');
    // print_r($address);

    // $lien = '/v2/accounts/%id_account%/addresses/%code%';
    $lien = '/v2/accounts/id_account/addresses/address/transactions';

    // $lien = explode('%', $lien);
    // print_r(extract($lien));

    $new = sett($lien, ['bonjour', 'qwertyu']);
    print_r($new);


    function sett($str, $vars = []){
        try{
            $array = explode('/', $str);
            $j = -1;
            foreach($array as $value){
                if (strpos($value, ':') !== false) {
                    $str = str_replace($value, $vars[++$j], $str);
                }
            }
            return $str;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

?>