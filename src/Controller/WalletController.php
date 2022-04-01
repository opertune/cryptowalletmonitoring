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

        return $this->render('main/wallet.html.twig', [
            'AddWalletForm' => $addWalletForm->createView(),
            'wallets' => $user->getWallet(),
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
        switch ($addWalletForm->get('name')->getData()) {
            case 'Binance':
                // get binance balances and set it in wallet entity
                $binance = new Binance($addWalletForm->get('apiKey')->getData(), $addWalletForm->get('secretKey')->getData());
                $wallet->setDataJson($binance->getBinanceBalances());
                break;
            case 'Gate.io':
                $gate = new Gate($addWalletForm->get('apiKey')->getData(), $addWalletForm->get('secretKey')->getData());
                $wallet->setDataJson($gate->getGateBalances());
                break;
            case 'Kucoin':
                $kucoin = new Kucoin($addWalletForm->get('apiKey')->getData(), $addWalletForm->get('secretKey')->getData(), $addWalletForm->get('passPhrase')->getData());
                $wallet->setDataJson($kucoin->getKucoinBalance());
                break;
            case 'FTX':
                $ftx = new Ftx($addWalletForm->get('apiKey')->getData(), $addWalletForm->get('secretKey')->getData());
                $wallet->setDataJson($ftx->getFtxBalance());
                break;
            case 'Coinbase':
                $coinbase = new Coinbase($addWalletForm->get('apiKey')->getData(), $addWalletForm->get('secretKey')->getData());
                $wallet->setDataJson($coinbase->getCoinbaseBalance());
                break;
        }
        // Add new wallet in database
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        // Update new wallet with total value for each coin
        Utils::updateWalletPrice($this->userRepository->findOneByEmail($this->getUser()->getUserIdentifier()), $this->priceRepository, $this->entityManager);
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
        // Update user dataJson with total for each coin
        Utils::updateWalletPrice($user, $this->priceRepository, $this->entityManager);
        // redirect with success message
        $this->addFlash('flash_success', 'Price updated successfully');
        return $this->redirectToRoute('wallet');

        // Faire en sorte que getallcoinsprice se déclanche tout les x temps sans que l'utilisateur ai à le faire de lui même
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

        $this->addFlash('flash_success', 'Geted all coins price successfully');
        return $this->redirectToRoute('wallet');
    }
}
