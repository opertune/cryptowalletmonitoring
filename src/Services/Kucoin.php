<?php

namespace App\Service\Kucoin;

class Kucoin
{
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey, $sercretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $sercretKey;
    }

    public function getKucoinBalance()
    {
        $baseUrl = 'https://api.kucoin.com';
        $urlRequest = '/api/v1/accounts';
    }
}
