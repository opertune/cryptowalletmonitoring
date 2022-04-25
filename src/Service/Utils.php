<?php

namespace App\Service;

use App\Repository\PriceRepository;
use App\Service\Exchange\Binance;
use App\Service\Exchange\Coinbase;
use App\Service\Exchange\Ftx;
use App\Service\Exchange\Gate;
use App\Service\Exchange\Kucoin;

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
        }

        return $coinArray->getBalance($priceRepository);
    }

    /**
     * Encrypting a message with openssl_encrypt
     */
    public static function encrypt(string $encryptionKey, string $initializationVector, $data): string
    {
        return openssl_encrypt(serialize($data), 'AES-256-CBC', $encryptionKey, 0, $initializationVector);
    }

    /**
     * Decrypting a message with openssl_decrypt
     */
    public static function decrypt(string $encryptionKey, string $initializationVector, $data)
    {
        return unserialize(openssl_decrypt($data, 'AES-256-CBC', $encryptionKey, 0, $initializationVector));
    }

    /**
     * Generate au key with random char
     */
    public static function randomChar(int $bytes)
    {
        $string = '1234567890abcdefghijklmnopqrstuvxyz!@#$%^&*()_+:;.,<>/?\|{[]}';
        $randomString = '';
        for ($i = 0; $i < $bytes; $i++) {
            $randomString .= $string[rand(0, 60)];
        }

        return $randomString;
    }
}
