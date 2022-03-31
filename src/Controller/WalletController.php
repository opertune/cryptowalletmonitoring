<?php

namespace App\Controller;

use App\Entity\Price;
use App\Entity\User;
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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Page for showing all wallet information
     * @Route("/wallet", name="wallet")
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // Get current user
        $user = $userRepository->findOneByEmail($this->getUser()->getUserIdentifier());

        // Create add wallet form
        $addWalletForm = $this->createForm(addWallet::class);
        $addWalletForm->handleRequest($request);

        // Add new wallet in db
        if ($addWalletForm->isSubmitted() && $addWalletForm->isValid()) {
            $this->addWallet($addWalletForm);
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
    public function byExchange($id, UserRepository $userRepository, WalletRepository $walletRepository): Response
    {
        // Get current user
        $user = $userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        // Get selected wallet by id
        $wallet = $walletRepository->find($id);

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
        $name = $wallet->getName();
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();
        $this->addFlash('flash_success', "$name wallet successfully added");
    }

    /**
     * Remove selected wallet in database
     * @Route("/removeWallet/{id}", name="removeWallet")
     */
    public function removeWallet($id, UserRepository $userRepository, WalletRepository $walletRepository)
    {
        // Get current user
        $user = $userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        // Get selected wallet by id
        $wallet = $walletRepository->find($id);

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

    /**
     * @Route("/updateWalletPrice", name="updateWalletPrice")
     */
    public function updateWalletPrice(UserRepository $userRepo, PriceRepository $priceRepo)
    {
        $user = $userRepo->findOneByEmail($this->getUser()->getUserIdentifier());
        // Get user wallet
        foreach ($user->getWallet() as $wallet) {
            // FTX and Gate.io already have the value included
            if ($wallet->getName() != 'FTX' && $wallet->getName() != 'Gate.io') {
                $arr = [];
                // Get all coin quantity and name in the wallet
                foreach ($wallet->getDataJson() as $val) {
                    // Find price relative to the name
                    $price = $priceRepo->findBySymbol(strtolower($val['symbol']));
                    // If the coin market cap is less than 100000, $price == null, therefore we don't have the price
                    $price != null ? $value = number_format($price->getPrice() * $val['quantity'], 2, '.', ',') : $value = 0;
                    array_push($arr, array(
                        'symbol' => $val['symbol'],
                        'quantity' => $val['quantity'],
                        'value' => $value
                    ));
                }
                // Update user wallet in database
                $wallet->setDataJson($arr);
                $this->entityManager->persist($wallet);
                $this->entityManager->flush();
            }
        }
        $this->entityManager->clear();
        $this->addFlash('flash_success', 'Price updated successfully');
        return $this->redirectToRoute('wallet');

        // Faire en sorte que getallcoinsprice se déclanche tout les x temps sans que l'utilisateur ai à le faire de lui même
        // Faire le calcul et ajouter le montant en usdt dans le dataJson de chaque wallet (à l'ajout du wallet)

        // Faire un bouton update qui permet à l'utilisateur d'update ses wallet avec le nouveau prix retourné par getallcoinprince et d'update le contenu de ses wallet
    }
}
