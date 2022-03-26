<?php

namespace App\Service\Gate;

use Symfony\Component\HttpFoundation\Request;

class Gate
{
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function getGateBalances()
    {
        $timestamp = time();
        $hashedPayload = hash("sha512", "");
        $fmt = "%s\n%s\n%s\n%s\n%s";
        $signatureString = sprintf(
            $fmt,
            'GET',
            '/api/v4/wallet/total_balance',
            'currency=USDT',
            $hashedPayload,
            $timestamp,
        );

        // SIGNATURE
        $signature = hash_hmac("sha512", $signatureString, $this->secretKey);

        // HEADER
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "KEY: $this->apiKey",
            "SIGN: $signature",
            "Timestamp: $timestamp",
        );

        $urlRequest = 'https://api.gateio.ws/api/v4/wallet/total_balance?currency=USDT';
        $querry = curl_init($urlRequest);
        curl_setopt($querry, CURLINFO_HEADER_OUT, true);
        curl_setopt($querry, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($querry, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($querry, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($querry, CURLOPT_URL, $urlRequest);
        $datas = array(json_decode(curl_exec($querry), true));
        // $h = curl_getinfo($querry, CURLINFO_HEADER_OUT);
        curl_close($querry);
        $total = [];
        array_push($total, array(
            'asset' => $datas[0]['total']['currency'],
            'quantity' => $datas[0]['total']['amount']
        ));
        return $total;
    }
}
