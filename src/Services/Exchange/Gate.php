<?php

namespace App\Service\Gate;

use App\Repository\PriceRepository;
use App\Service\Utils\Utils;

class Gate
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

        $url = 'https://api.gateio.ws/api/v4/wallet/total_balance?currency=USDT';
        $datas = Utils::curlRequest($url, $headers);

        $coins = [];
        $price = $priceRepository->findBySymbol(strtolower($datas['total']['currency']));
        $price != null ? $value = number_format($price->getPrice() * $datas['total']['amount'], 2, '.', ',') : $value = 0;
        array_push($coins, array(
            'symbol' => $datas['total']['currency'],
            'quantity' => number_format($datas['total']['amount'], 2, '.', ','),
            'value' => $value,
        ));

        // Return sorted array in the value column with symbol, quantity and value
        return Utils::sortArray($coins, 'value', SORT_DESC);
    }
}
