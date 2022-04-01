<?php

namespace App\Service\Utils;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\PriceRepository;
use App\Repository\UserRepository;
use App\Service\Binance\Binance;
use App\Service\Coinbase\Coinbase;
use App\Service\Ftx\Ftx;
use App\Service\Gate\Gate;
use App\Service\Kucoin\Kucoin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class Utils
{
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
            // Update user wallet in database
            $wallet->setDataJson($arr);
            $entityManager->persist($wallet);
            $entityManager->flush();
        }
        $entityManager->clear();
    }
}
