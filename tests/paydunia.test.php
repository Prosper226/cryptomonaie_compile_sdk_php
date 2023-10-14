<?php
    use Dunya\Dunya;
    require(dirname(__DIR__, 1).'/vendor/autoload.php');

    try{
        $Dunya = new Dunya("MYAPP");

        /** 
         * 
         * TEST INTEGRATION DE PAIEMENTS (DEPOTS) 
         * 
         */
        // Burkina 
        // $deposit = $Dunya->deposit('BF', 'ORANGE', ["phone" => 57474578, "amount" => 200, "bash" => "D001", "otp" => 997419]);
        // $deposit = $Dunya->deposit('BF', 'MOOV', ["phone" => 60565103, "amount" => 200, "bash" => "D002"]);
        
        // Benin *
        // $deposit = $Dunya->deposit('BN', 'MTN', ["phone" => 57474578, "amount" => 200, "bash" => "D031"]);   // approuved
        // $deposit = $Dunya->deposit('BN', 'MOOV', ["phone" => 60565103, "amount" => 200, "bash" => "D004"]);
        
        // Togo *
        // $deposit = $Dunya->deposit('TG', 'TMONEY', ["phone" => 90239415, "amount" => 200, "bash" => "DT021"]);    // disponible
        
        // Cote d'ivoire
        // $deposit = $Dunya->deposit('CI', 'ORANGE', ["phone" => 57474578, "amount" => 200, "bash" => "D006", "otp" => 997419]);   // disponible
        // $deposit = $Dunya->deposit('CI', 'MTN', ["phone" => 60565103, "amount" => 200, "bash" => "D007"]);                       // disponible
        // $deposit = $Dunya->deposit('CI', 'MOOV', ["phone" => 60565103, "amount" => 200, "bash" => "D008"]);                      // disponible
        
        // senegal
        // $deposit = $Dunya->deposit('SN', 'ORANGE', ["phone" => 771902641, "amount" => 1500, "bash" => "D0810221136", "otp" => 717240]); // approuved // 775842306
        // $deposit = $Dunya->deposit('SN', 'FREEMONEY', ["phone" => 60565103, "amount" => 200, "bash" => "D007"]);
        // $deposit = $Dunya->deposit('SN', 'EXPRESSO', ["phone" => 60565103, "amount" => 200, "bash" => "D008"]);  // disponible
        // $deposit = $Dunya->deposit('SN', 'WAVE', ["phone" => 60565103, "amount" => 200, "bash" => "D008"]);      // disponible

        $deposit = $Dunya->deposit('ML', 'ORANGE', ["phone" => 90900059, "amount" => 200, "bash" => "D0260923", "otp" => 997419]);
        print_r($deposit ?? []);

        /**
         * 
         * TEST ANALYSE DE LA RECEPTION DU CALLBACK DE DEPOT
         * 
         */
        // $dunyaCallbackResponse =  array(
        //     'response_code' => "00",
        //     'response_text' => "Transaction Found",
        //     'hash' => "d3e50f40a3df88228d170c4952ef9f4b9349f9d660fe4242ce587fbfccababc98a87fdbac75fb5ce8b2a0d0546ad3363b71f557f1af2e018067ee4a678df1f1a",
        //     'invoice' => array(
        //             'token' => "8UGeZKbJEyM04Zi39ruf",
        //             'pal_is_on' => 0,
        //             'total_amount' => 200,
        //             'total_amount_without_fees' => 200.00,
        //             'description' => "Paiement vers BARKACHANGE (ORANGE - SENEGAL)",
        //             'expire_date' => "2022-10-07 11:47:16"
        //         ),
        
        //     'custom_data' => array(
        //             'phone' => "775842306",
        //             'amount' => 200,
        //             'bash' => "D006",
        //             'otp' => 204019
        //         ),
        
        //     'actions' => array(
        //             'cancel_url' => "",
        //             'callback_url' => "https://www.barkachange.com/dunya-pay-status298349712389190-120_yeah",
        //             'return_url' => ""
        //         ),
        
        //     'mode' => "live",
        //     'status' => "completed",
        //     'fail_reason' => "",
        //     'customer' => array (
        //             'name' => "John Doe",
        //             'phone' => 775842306,
        //             'email' => "test@gmail.com",
        //             'payment_method' => "orange_money_senegal"
        //         ),
        
        //     'receipt_identifier' => "PYDUNA-9505955115891929",
        //     'receipt_url' => "https://paydunya.com/checkout/receipt/pdf/XfrPkMDiMQliU1husOEh.pdf",
        //     'provider_reference' => "MP221007.1117.B81853"
        // );
        // $status = $Dunya->operationStatus($dunyaCallbackResponse);
        // print_r($status ?? []);

        /** 
         * 
         * TEST INTEGRATION DE TRANSFERTS (RETRAITS) 
         * 
         */
        // Senegal
        // $withdraw = $Dunya->withdraw('SN', 'ORANGE', ["phone" => 775842306, "amount" => 500, "bash" => "WT001"]);    // approuved
        // Benin
        // $withdraw = $Dunya->withdraw('BN', 'MTN', ["phone" => 66414231, "amount" => 200, "bash" => "W001"]);         // approuved
        // Togo
        // $withdraw = $Dunya->withdraw('TG', 'TMONEY', ["phone" => 60565103, "amount" => 200, "bash" => "D005"]);      // approuved
        // print_r($withdraw ?? []);


        // $balance = $Dunya->balance();
        // print_r($balance);

    }catch(Exception $e){
        echo "Error: " . $e->getMessage();
    }



// <!-- https://developers.paydunya.com/doc/FR/introduction -->
// <!-- composer install --ignore-platform-reqs -->
// <!-- Avant d'initier un paiement il faut generer un token -->


// $finding = $Dunya->findByToken('CzkWpr8TOhBb6C2cianT');
// print_r($finding ?? []);

?>


