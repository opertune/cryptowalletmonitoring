<?php

namespace App\Service\Exchange;

use App\Repository\PriceRepository;
use App\Service\Utils;
use Exception;

class Binance
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
        /**
         * This api requires signed endpoint.
         * Endpoint use Hmac sha256 signatures.
         * The signature is a keyed HMAC SHA 256 and secretKey.
         * SecretKey is key and params is value for hmac operation.
         */
        $timestamp = round(microtime(true) * 1000);
        $recvWindow = 60000;
        $params = http_build_query([
            'timestamp' => $timestamp,
            'recvWindow' => $recvWindow
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
        if (isset($data)) {
            // Put coins > 0 in array
            $coins = [];
            foreach ($datas['balances'] as $currency) {
                if ($currency['free'] > 0.00000000 || $currency['locked'] > 0.00000000) {
                    // Binance return wrong name (and Binance don't return id...)
                    if ($currency['asset'] == 'DATA') {
                        $currency['asset'] = 'XDATA';
                    }

                    $price = $priceRepository->findBySymbol(strtolower($currency['asset']));
                    $price != null ? $value = number_format($price->getPrice() * ($currency['free'] + $currency['locked']), 2, '.', ',') : $value = 0;
                    array_push($coins, array(
                        'symbol' => $currency['asset'],
                        'quantity' => $currency['free'] + $currency['locked'],
                        'value' => $value
                    ));
                }
            }

            // Return sorted array in the value column with symbol, quantity and value
            return Utils::sortArray($coins, 'value', SORT_DESC);
        } else {
            return array(
                'errorID' => $datas['code'],
                'errorMessage' => $datas['msg'],
            );
        }
    }
}
