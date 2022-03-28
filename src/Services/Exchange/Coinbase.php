<?php

namespace App\Service\Coinbase;

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
        $signatureString = $timestamp . 'GET' . '/accounts';
        $signature = hash_hmac('sha256', $signatureString, $this->secretKey);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "CB-ACCESS-KEY: $this->apiKey",
            "CB-ACCESS-SIGN: $signature",
            "CB-ACCESS-TIMESTAMP: $timestamp",
        );

        $querry = curl_init($url);
        curl_setopt($querry, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($querry, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($querry, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($querry, CURLOPT_URL, $url);
        $datas = array(json_decode(curl_exec($querry), true));
        curl_close($querry);

        return $datas;
    }
}
