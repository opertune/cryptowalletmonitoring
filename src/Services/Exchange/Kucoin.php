<?php

namespace App\Service\Kucoin;

class Kucoin
{
    private $apiKey;
    private $secretKey;
    private $passPhrase;

    public function __construct($apiKey, $sercretKey, $passPhrase)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $sercretKey;
        $this->passPhrase = $passPhrase;
    }

    public function getKucoinBalance(): ?array
    {
        $url = 'https://api.kucoin.com/api/v1/accounts';
        $timestamp = time() * 1000;

        $signatureString = $timestamp . 'GET' . '/api/v1/accounts';
        $signature = base64_encode(hash_hmac('sha256', $signatureString, $this->secretKey, true));

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "KC-API-KEY: $this->apiKey",
            "KC-API-SIGN: $signature",
            "KC-API-TIMESTAMP: $timestamp",
            "KC-API-PASSPHRASE: $this->passPhrase",
            "KC-API-KEY-VERSION: 1"
        );

        $querry = curl_init($url);
        curl_setopt($querry, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($querry, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($querry, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($querry, CURLOPT_URL, $url);
        $datas = array(json_decode(curl_exec($querry), true));
        curl_close($querry);

        // Get coins with balance greater than 0 and put it in array
        $coins = [];
        foreach ($datas[0]['data'] as $currency) {
            if ($currency['balance'] > 00.00000000) {
                array_push($coins, array(
                    'symbol' => $currency['currency'],
                    'quantity' => $currency['balance']
                ));
            }
        }
        return $coins;
    }
}
