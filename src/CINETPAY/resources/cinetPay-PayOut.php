<?php


namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CinetPayService
{
    public $token = [
        'token' => null,
        'expiry' => null
    ];

    protected $cinetPayApiBaseUrl = 'https://client.cinetpay.com/v1';
    protected $apiKey = '';
    protected $apiPassword = '';

    public function getToken()
    {
        if (!empty($this->token['token']) && !empty($this->token['expiry']) && $this->token['expiry'] < date('Y-m-d H:i:s')) {
            return $this->token;
        }

        $url = $this->cinetPayApiBaseUrl . '/auth/login';
        $credentials = [
            'apikey' => $this->apiKey,
            'password' => $this->apiPassword,
        ];
        $response = $this->fetchCurlFormEncoded($url, $credentials);
        if ((int)$response->code == 0) {
            $this->token['token'] = $response->data->token;
            $this->token['expiry'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + 10 minute'));
            return $this->token['token'];
        }
        throw new \Exception('Failed to get Token from CinetPay.');
        return false;
    }

    public function sendMoney($transfer)
    {
        $transferId = $transfer['transfer_id'];
        $prefix = $transfer['prefix'];
        $phone = $transfer['phone'];
        $amount = $transfer['amount'];
        $notifyUrl = $transfer['notify_url'];
        $countryIso2 = $transfer['country_iso'];// Iso2 mean value like CI, US, IN

        $url = $this->cinetPayApiBaseUrl . "/transfer/money/send/contact?token=" .
            $this->getToken() . "&lang=fr&transaction_id=" .$transferId;
        try {
            $this->addContact($transfer);
        } catch (\Exception $e) {
            echo json_encode($e);
        }

        $balance = $this->checkBalance($countryIso2);
        if ($balance < $amount) {
            throw new \Exception('You don\'t have enough balance');
        }

        $params[] = [
            'prefix' => $prefix,
            'phone' => $phone,
            'amount' => $amount,
            'notify_url' => $notifyUrl,
            'client_transaction_id' => $transferId,
        ];
        $data = array('data' => json_encode($params));
        $response = $this->fetchCurlFormEncoded($url, $data);

        if (empty($response)) {
            return false;
        }

        if ($response->code == 0) {
            $response = $response->data[0][0];
            //Save the response content in your DB
            return true;
        } elseif ($response->code == -1) {
            //CinetPay Refuse transaction;
        } elseif ($response->code == 804) {
            //CinetPay Refuse transaction: Operator unavailable
        } elseif ($response->code == 602) {
            //Insufficient funds
        } else {
            //Something happened with transaction
        }
 
        return false;
    }

    public function addContact($transfer)
    {
        $url = $this->cinetPayApiBaseUrl . '/transfer/contact?token=' . $this->getToken() . '&lang=fr';
        $email = '';
        $phone = $transfer['phone'];
        $prefix = $transfer['prefix'];
        $name = 'BeneficiaryName : '. $transfer[''];
        $surname =  'BeneficiarySurname : ' . trim($transfer['']);

        $params[] = [
            'prefix' => $phone,
            'phone' => $prefix,
            'name' => $name,
            'surname' => $surname,
            'email' => $email ?? 'dummy@dummy.com'
        ];
        $data = array('data' => json_encode($params));
        $response = $this->fetchCurlFormEncoded($url, $data);
        return $response;
    }

    public function checkBalance(string $countryIso)
    {
        $url = $this->cinetPayApiBaseUrl . '/transfer/check/balance?token=' . $this->getToken() . '&lang=fr';
        try {
            $response = $this->fetch($url);
            if ($response && $response['code'] == 0) {
                $countryBalances = $response['data']['countryBalance'];
                if (array_key_exists($countryIso, $countryBalances)) {
                    return $countryBalances[$countryIso]['available'];
                }
            }
        } catch (\Exception $e) {
        }
        return 0;
    }

    public function checkTransactionStatus($transaction_id)
    {
        $url = $this->cinetPayApiBaseUrl . "/transfer/check/money?token=" . $this->getToken() . "&client_transaction_id=$transaction_id" ;
        try {
            $cpResponse = $this->fetch($url);
            if (array_key_exists('data', $cpResponse)) {
                $cpTransactions = $cpResponse['data'];
                if (empty($cpTransactions)) {
                    return false;
                }
                return $cpTransactions[0];
            }
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $cpResponse = json_decode((string)$response->getBody());
                if ($cpResponse->message == "NOT_FOUND") {
                    //TODO ALERT TRANSACTION_ID NOT IN CP
                }
            }
        }
        return false;
    }


    private function fetchCurlFormEncoded(string $url, array $data = [])
    {
        if (function_exists('curl_version')) {
            try {
                $ch = curl_init();
                $payload = $this->hydrateFormParameters($data);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                if (!empty($data)) {
                    $params = $payload;
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                }
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close($ch);

                if ($server_output) {
                    return json_decode($server_output);
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
        } elseif (ini_get('allow_url_fopen')) {
            try {
                // Build Http query using params
                $query = http_build_query($data);
                // Create Http context details
                $options = array(
                    'http' => array(
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                            "Content-Length: " . strlen($query) . "\r\n" .
                            "User-Agent:MyAgent/1.0\r\n",
                        'method' => "POST",
                        'content' => $query,
                    ),
                );
                // Create context resource for our request
                $context = stream_context_create($options);
                // Read page rendered as result of your POST request
                $result = file_get_contents(
                    $url, // page url
                    false,
                    $context
                );
                return trim($result);
            } catch (\Exception $e) {
                info(json_encode($e->getMessage()));
                throw new \Exception($e);
            }
        } else {
            throw new \Exception("You must enable curl or allow_url_fopen to use CinetPay");
        }
    }

    private function hydrateFormParameters(array $items): string
    {
        $params = '';
        foreach ($items as $key => $item) {
            $params .= "$key=$item&";
        }
        $params = substr($params, 0, -1);
        return $params;
    }

    public static function fetch($url, $data = false)
    {
        $guzClient = new Client(['base_uri' => $url]); //GuzzleHttp\Client
        if ($data) {
            try {
                $response = $guzClient->request('POST', '', ['json' => $data]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            $response = $guzClient->request('GET');
        }
        return json_decode($response->getBody()->getContents(), true);
    }
}
