<?php

namespace App\Service\Coinbase;

use App\Service\Utils\Utils;

class Coinbase
{
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function getCoinbaseBalance()
    {
        $url = 'https://api.coinbase.com/v2/accounts';
        $timestamp = time();
        $signatureString = $timestamp . 'GET' . '/v2/accounts';
        $signature = hash_hmac('sha256', $signatureString, $this->secretKey);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "CB-ACCESS-KEY: $this->apiKey",
            "CB-ACCESS-SIGN: $signature",
            "CB-ACCESS-TIMESTAMP: $timestamp",
            "CB-VERSION: 2015-04-08",
        );

        $datas = Utils::curlRequest($url, $headers);

        $coins = [];
        foreach ($datas['data'] as $currency) {
            if ($currency['balance']['amount'] > 0.000) {
                array_push($coins, array(
                    'symbol' => $currency['balance']['currency'],
                    'quantity' => $currency['balance']['amount'],
                ));
            }
        }

        return $coins;
    }
}
