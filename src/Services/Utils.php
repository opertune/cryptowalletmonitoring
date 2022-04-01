<?php

namespace App\Service\Utils;

use App\Entity\User;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;

class Utils
{
    /**
     * Update column dataJson for each wallet with total value for each coin (coin quantity * coin price)
     */
    public static function updateWalletPrice(User $user, PriceRepository $priceRepo, EntityManagerInterface $entityManager)
    {
        foreach ($user->getWallet() as $wallet) {
            $arr = [];
            $total = 0;
            // Get all coin quantity and name in the wallet
            foreach ($wallet->getDataJson() as $val) {
                // Find price relative to the name
                $price = $priceRepo->findBySymbol(strtolower($val['symbol']));
                // If the coin market cap is less than 10000, $price == 0
                $price != null ? $value = number_format($price->getPrice() * $val['quantity'], 2, '.', ',') : $value = 0;

                // coingecko doesn't take usd
                if ($val['symbol'] == 'USD') {
                    $value = number_format($val['quantity'], 2, '.', ',');
                }

                // Total wallet value
                $total += $value;

                // Update datajson array with total and coin value
                array_push($arr, array(
                    'symbol' => $val['symbol'],
                    'quantity' => $val['quantity'],
                    'value' => $value
                ));
            }

            // Array sort relative to 'value' column
            $col = array_column($arr, 'value');
            array_multisort($col, SORT_DESC, $arr);

            // Update user wallet in database
            $wallet->setDataJson($arr);
            $entityManager->persist($wallet);
            $entityManager->flush();
        }
        $entityManager->clear();
    }

    /**
     * Curl request for exchange api
     * return an array with user wallet data
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
}
