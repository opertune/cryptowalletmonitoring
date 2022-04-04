<?php

namespace App\Controller;

use App\Entity\Price;
use App\Entity\Wallet;
use App\Form\addWallet;
use App\Repository\PriceRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\Binance\Binance;
use App\Service\Coinbase\Coinbase;
use App\Service\Ftx\Ftx;
use App\Service\Gate\Gate;
use App\Service\Kucoin\Kucoin;
use App\Service\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Break_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PriceRepository $priceRepository;
    private UserRepository $userRepository;
    private WalletRepository $walletRepository;

    public function __construct(EntityManagerInterface $entityManager, PriceRepository $priceRepository, UserRepository $userRepository, WalletRepository $walletRepository)
    {
        $this->entityManager = $entityManager;
        $this->priceRepository = $priceRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
    }

    /**
     * Page for showing all wallet information
     * @Route("/wallet", name="wallet")
     */
    public function index(Request $request): Response
    {
        // Get current user
        $user = $this->userRepository->findOneByEmail($this->getUser()->getUserIdentifier());

        // Create add wallet form
        $addWalletForm = $this->createForm(addWallet::class);
        $addWalletForm->handleRequest($request);

        // Add new wallet in db
        if ($addWalletForm->isSubmitted() && $addWalletForm->isValid()) {
            $this->addWallet($addWalletForm, $this->priceRepository);
            return $this->redirect($request->getUri());
        }

        // Get all wallet total value and each wallet total value
        $allWalletTotal = 0;
        $eachWalletTotal = [];
        foreach ($user->getWallet() as $wallet) {
            $walletTotal = 0;
            foreach ($wallet->getDataJson() as $data) {
                $allWalletTotal += $data['value'];
                $walletTotal += $data['value'];
            }
            array_push($eachWalletTotal, $walletTotal);
        }

        return $this->render('main/wallet.html.twig', [
            'AddWalletForm' => $addWalletForm->createView(),
            'wallets' => $user->getWallet(),
            'allWalletTotal' => $allWalletTotal,
            'eachWalletTotal' => $eachWalletTotal,
        ]);
    }

    /**
     * Page for showing more information from selected wallet by exchange
     * @Route("/wallet/{id}", name="byexchange")
     */
    public function byExchange($id): Response
    {
        // Get current user
        $user = $this->userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        // Get selected wallet by id
        $wallet = $this->walletRepository->find($id);

        // Check if logged user has the selected wallet
        if ($user->getWallet()->contains($wallet)) {
            return $this->render('main/walletByExchange.html.twig', [
                'wallet' => $wallet,
            ]);
        } else {
            $this->addFlash('flash_error', 'Can\'t show this wallet');
            return $this->redirectToRoute('wallet');
        }
    }

    /**
     * Add new user wallet in database
     */
    public function addWallet($addWalletForm)
    {
        // Init new wallet
        $wallet = new Wallet();
        $wallet->setAccount($this->getUser())
            ->setName($addWalletForm->get('name')->getData())
            ->setApiKey($addWalletForm->get('apiKey')->getData())
            ->setSecretKey($addWalletForm->get('secretKey')->getData())
            ->setPassPhrase($addWalletForm->get('passPhrase')->getData());

        // Set wallet data relative to selected exchange
        $data = Utils::apiCall($addWalletForm->get('name')->getData(), $addWalletForm->get('apiKey')->getData(), $addWalletForm->get('secretKey')->getData(), $addWalletForm->get('passPhrase')->getData(), $this->priceRepository);
        $wallet->setDataJson($data);
        // Add new wallet in database
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        // Update new wallet with total value for each coin
        $name = $wallet->getName();
        $this->addFlash('flash_success', "$name wallet successfully added");
    }

    /**
     * Remove selected wallet in database
     * @Route("/removeWallet/{id}", name="removeWallet")
     */
    public function removeWallet($id)
    {
        // Get current user
        $user = $this->userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        // Get selected wallet by id
        $wallet = $this->walletRepository->find($id);

        // Check if logged user has the selected wallet
        if ($user->getWallet()->contains($wallet)) {
            // Remove wallet and update database
            $user->removeWallet($wallet);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            $this->addFlash('flash_success', 'Wallet successfully removed');
        } else {
            $this->addFlash('flash_error', 'Can\'t remove wallet');
        }

        return $this->redirectToRoute('wallet');
    }

    /**
     * @Route("/updateWalletPrice", name="updateWalletPrice")
     */
    public function updateWalletPrice()
    {
        // Get current user
        $user = $this->userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        // Update user dataJson with total value for each coin
        foreach ($user->getWallet() as $wallet) {
            $data = Utils::apiCall($wallet->getName(), $wallet->getApiKey(), $wallet->getSecretKey(), $wallet->getPassPhrase(), $this->priceRepository);
            $wallet->setDataJson($data);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();
        }
        $this->entityManager->clear();

        // redirect with success message
        $this->addFlash('flash_success', 'Price updated successfully');
        return $this->redirectToRoute('wallet');
    }









    /**
     * @Route("/getAllCoinsPrice", name="getAllCoinsPrice")
     * Get all coin price from coingecko
     */
    public function getAllCoinsPrice()
    {
        $page = 1;
        $client = HttpClient::create();

        // Deleted all content in price table
        $this->entityManager->getConnection()->prepare('TRUNCATE TABLE price')->executeQuery();
        do {
            $response = $client->request('GET', 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=250&page=' . $page . '&sparkline=false&price_change_percentage=true');
            $response = $response->toArray();
            foreach ($response as $val) {
                $price = new Price();
                $price->setSymbol($val['symbol'])
                    ->setPrice($val['current_price']);
                $this->entityManager->persist($price);
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
            $page++;
        } while ($response[249]['market_cap'] > 10000);

        // Add usd price manually because coingecko does not propose it
        $usd = new Price();
        $usd->setSymbol('usd')
            ->setPrice(1);
        $this->entityManager->persist($usd);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->addFlash('flash_success', 'Geted all coins price successfully');
        return $this->redirectToRoute('wallet');
    }
}


// Faire en sorte que getallcoinsprice se déclanche tout les x temps sans que l'utilisateur ai à le faire de lui même
// https://symfony.com/doc/current/the-fast-track/fr/24-cron.html

// Crypter toutes les données sauf l'email

// Ajouter des test
// Ajouter de fixture (ajout de donnée random pour les test)


// RÉSOLU AVEC LA METHODE apiCall DANS UTILS -> je l'utilise dans addWallet et updateWallet
// Problème: Si l'utilisateur achète ou vend une crypto elle n'est pas ajouté dans les wallet
// Solution: Sur le boutton Update wallet price ajouter un update des wallet des quantité / nom