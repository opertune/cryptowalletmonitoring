<?php

namespace App\Service\Exchange;

use App\Repository\PriceRepository;
use App\Service\Utils;

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

    public function getBalance(PriceRepository $priceRepository): ?array
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

        $datas = Utils::curlRequest($url, $headers);

        // Get coins with balance greater than 0 and put it in array
        $coins = [];
        foreach ($datas['data'] as $currency) {
            if ($currency['balance'] > 00.00000000) {
                $price = $priceRepository->findBySymbol(strtolower($currency['currency']));
                $price != null ? $value = number_format($price->getPrice() * $currency['balance'], 2, '.', ',') : $value = 0;
                array_push($coins, array(
                    'symbol' => $currency['currency'],
                    'quantity' => $currency['balance'],
                    'value' => $value,
                ));
            }
        }

        // Return sorted array in the value column with symbol, quantity and value
        return Utils::sortArray($coins, 'value', SORT_DESC);
    }
}
