<?php

namespace App\Service\Ftx;

class Ftx
{
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function getFtxBalance(): ?array
    {
        $url = 'https://ftx.com/api/wallet/balances';
        $timestamp = time() * 1000;

        $signatureString = $timestamp . 'GET/api' . '/wallet/balances';
        $signature = hash_hmac('sha256', $signatureString, $this->secretKey);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "FTX-KEY: $this->apiKey",
            "FTX-TS: $timestamp",
            "FTX-SIGN: $signature",
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
        foreach ($datas[0]['result'] as $currency) {
            if ($currency['total'] > 0.0) {
                array_push($coins, array(
                    'symbol' => $currency['coin'],
                    'quantity' => $currency['total'],
                    'value' => $currency['usdValue']
                ));
            }
        }

        return $coins;
    }
}
