<?php

namespace App\Service\Binance;

use App\Service\Utils\Utils;

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
            'timestamp' => time() * 1000,
            'recvWindow' => 60000
        ], '', '&');

        $signature = hash_hmac('sha256', $params, $this->secretKey);

        $url = 'https://api.binance.com/api/v3/account?' . $params . '&signature=' . $signature;
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            'X-MBX-APIKEY:' . $this->apiKey,
        );
        /**
         * Binance api request with curl
         */
        $datas = Utils::curlRequest($url, $headers);

        // Put coins > 0 in array
        $coins = [];
        foreach ($datas['balances'] as $data) {
            if ($data['free'] > 0.00000000 || $data['locked'] > 0.00000000) {
                // Binance return wrong name (and Binance don't return id...)
                if ($data['asset'] == 'DATA') {
                    $data['asset'] = 'XDATA';
                }
                array_push($coins, array(
                    'symbol' => $data['asset'],
                    'quantity' => $data['free'] + $data['locked'],
                ));
            }
        }

        return $coins;
    }
}
