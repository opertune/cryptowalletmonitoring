<?php

namespace App\Service\Utils;

use App\Repository\PriceRepository;
use App\Service\Binance\Binance;
use App\Service\Coinbase\Coinbase;
use App\Service\Ftx\Ftx;
use App\Service\Gate\Gate;
use App\Service\Kucoin\Kucoin;

class Utils
{
    /**
     * Curl request for exchange api
     * return an array with user account data
     */
    public static function curlRequest(string $url, array $headers): ?array
    {
        $querry = curl_init($url);
        curl_setopt($querry, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($querry, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($querry, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($querry, CURLOPT_URL, $url);
        $datas = json_decode(curl_exec($querry), true);
        curl_close($querry);

        return $datas;
    }

    /**
     * Sort an array
     */
    public static function sortArray(array $array, string $col, $method): ?array
    {
        $col = array_column($array, $col);
        array_multisort($col, $method, $array);
        return $array;
    }

    /**
     * Call api and return an array with balance of selected wallet
     */
    public static function apiCall(string $walletName, string $apiKey, string $secretKey, string $passPhrase = null, PriceRepository $priceRepository): ?array
    {
        switch ($walletName) {
            case 'Binance':
                // get binance balances and set it in wallet entity
                $coinArray = new Binance($apiKey, $secretKey);
                break;
            case 'Gate.io':
                $coinArray = new Gate($apiKey, $secretKey);
                break;
            case 'Kucoin':
                $coinArray = new Kucoin($apiKey, $secretKey, $passPhrase);
                break;
            case 'FTX':
                $coinArray = new Ftx($apiKey, $secretKey);
                break;
            case 'Coinbase':
                $coinArray = new Coinbase($apiKey, $secretKey);
                break;
        }

        return $coinArray->getBalance($priceRepository);
    }
}
