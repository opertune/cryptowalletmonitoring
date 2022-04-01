<?php

namespace App\Service\Utils;

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
    public static function sortArray(array $array, string $col, $method)
    {
        $col = array_column($array, $col);
        array_multisort($col, $method, $array);
        return $array;
    }
}
