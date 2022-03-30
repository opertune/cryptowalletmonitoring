<?php

namespace App\Service\Binance;

class Binance
{
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function getBinanceBalances(): ?array
    {
        /**
         * This api requires signed endpoint.
         * Endpoint use Hmac sha256 signatures.
         * The signature is a keyed HMAC SHA 256 and secretKey.
         * SecretKey is key and params is value for hmac operation.
         */
        $params = http_build_query([
            'timestamp' => number_format(microtime(true) * 1000, 0, '.', ''),
            'recvWindow' => 60000
        ], '', '&');
        $signature = hash_hmac('sha256', $params, $this->secretKey);

        $url = 'https://api.binance.com/api/v3/account?' . $params . '&signature=' . $signature;

        /**
         * Binance api request with curl
         * Return json array with balances data
         */
        $querry = curl_init($url);
        curl_setopt($querry, CURLOPT_HTTPHEADER, array('X-MBX-APIKEY:' . $this->apiKey));
        curl_setopt($querry, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($querry, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($querry, CURLOPT_URL, $url);
        $datas = array(json_decode(curl_exec($querry), true));
        curl_close($querry);


        // Put coins > 0 in array with name, (Added locked coins with free coins)
        $coins = [];
        foreach ($datas[0]['balances'] as $data) {
            if ($data['free'] > 0.00000000 || $data['locked'] > 0.00000000) {
                array_push($coins, array(
                    'symbol' => $data['asset'],
                    'quantity' => $data['free'] + $data['locked']
                ));
            }
        }

        // Array sort relative to 'free' column
        // $col = array_column($coins, 'free');
        // array_multisort($col, SORT_ASC, $coins);

        return $coins;
    }
}
