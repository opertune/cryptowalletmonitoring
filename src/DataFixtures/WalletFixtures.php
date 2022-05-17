<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Wallet;
use App\Service\Utils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class WalletFixtures extends Fixture
{
    private $userPasswordHasher;
    private $encryptionKey;
    private $initializationVector;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, $encryptionKey, $initializationVector)
    {
        $this->userPasswordHasher = $userPasswordHasherInterface;
        $this->encryptionKey = $encryptionKey;
        $this->initializationVector = $initializationVector;
    }

    public function load(ObjectManager $manager): void
    {
        $walletName = array(
            'Binance',
            'Gate.io',
            'Kucoin',
            'FTX',
            'Coinbase'
        );

        $faker = \Faker\Factory::create('fr_FR');
        // Create 5 fake account
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setIsVerified(1)
                ->setPassword($this->userPasswordHasher->hashPassword(
                    $user,
                    '1234'
                ))
                ->setRoles(["ROLE_USER"]);
            $manager->persist($user);

            // For each account create 5 fake wallet
            for ($j = 0; $j < 5; $j++) {
                // Fake data array
                $fakeData = [];
                for ($k = 0; $k < rand(5, 10); $k++) {
                    array_push($fakeData, array(
                        'symbol' => $faker->tld(),
                        'quantity' => rand(1, 5),
                        'value' => rand(1, 100)
                    ));
                }
                $fakeData = Utils::encrypt(
                    $this->encryptionKey,
                    $this->initializationVector,
                    $fakeData
                );
                $wallet = new Wallet();
                $wallet->setAccount($user)
                    ->setName($walletName[$j])
                    ->setApiKey($faker->sha256)
                    ->setSecretKey($faker->sha256)
                    ->setPassPhrase(Utils::encrypt($this->encryptionKey, $this->initializationVector, $faker->sentence(3)))
                    ->setWalletData($fakeData);
                $manager->persist($wallet);
            }
        }

        $manager->flush();
    }
}
