<?php

namespace App\Service\Exchange;

use App\Repository\PriceRepository;
use App\Service\Utils;

class Coinbase
{
    private $apiKey;
    private $secretKey;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function getBalance(PriceRepository $priceRepository): ?array
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
            "CB-VERSION: 2022-03-28",
        );


        $datas = Utils::curlRequest($url, $headers);
        $coins = [];
        if (!array_key_exists('errors', $datas)) {
            foreach ($datas['data'] as $currency) {
                if ($currency['balance']['amount'] > 0.000) {
                    $price = $priceRepository->findBySymbol(strtolower($currency['balance']['currency']));
                    $price != null ? $value = number_format($price->getPrice() * $currency['balance']['amount'], 2, '.', ',') : $value = 0;

                    array_push($coins, array(
                        'symbol' => $currency['balance']['currency'],
                        'quantity' => $currency['balance']['amount'],
                        'value' => $value,
                    ));
                }
            }
            // Return sorted array in the value column with symbol, quantity and value
            return Utils::sortArray($coins, 'value', SORT_DESC);
        } else {
            return array(
                'errorID' => $datas['errors'][0]['id'],
                'errorMessage' => $datas['errors'][0]['message'],
            );
        }
    }
}
