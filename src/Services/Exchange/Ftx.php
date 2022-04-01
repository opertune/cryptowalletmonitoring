<?php

namespace App\Service\Ftx;

use App\Repository\PriceRepository;
use App\Service\Utils\Utils;

class Ftx
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

        $datas = Utils::curlRequest($url, $headers);

        // Get coins with balance greater than 0 and put it in array
        $coins = [];
        foreach ($datas['result'] as $currency) {
            if ($currency['total'] > 0.0) {
                // coingecko doesn't take usd
                $price = $priceRepository->findBySymbol(strtolower($currency['coin']));
                $price != null ? $value = number_format($price->getPrice() * $currency['total'], 2, '.', ',') : $value = 0;

                // coingecko doesn't take usd
                if ($currency['coin'] == 'USD') {
                    $value = number_format($currency['total'], 2, '.', ',');
                }

                array_push($coins, array(
                    'symbol' => $currency['coin'],
                    'quantity' => $currency['total'],
                    'value' => $value,
                ));
            }
        }

        // Return sorted array in the value column with symbol, quantity and value
        return Utils::sortArray($coins, 'value', SORT_DESC);
    }
}
