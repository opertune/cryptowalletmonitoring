<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\AddWalletType;
use App\Repository\PriceRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\Exchange\CoinbaseOauth;
use App\Service\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class WalletController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PriceRepository $priceRepository;
    private UserRepository $userRepository;
    private WalletRepository $walletRepository;
    private TranslatorInterface $translatorInterface;
    private HttpClientInterface $httpClientInterface;
    public function __construct(
        EntityManagerInterface $entityManager,
        PriceRepository $priceRepository,
        UserRepository $userRepository,
        WalletRepository $walletRepository,
        TranslatorInterface $translatorInterface,
        HttpClientInterface $httpClientInterface,
    ) {
        $this->entityManager = $entityManager;
        $this->priceRepository = $priceRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
        $this->translatorInterface = $translatorInterface;
        $this->httpClientInterface = $httpClientInterface;
    }

    /**
     * Page for showing all wallet information
     * @Route("/wallet", name="wallet")
     */
    public function index(Request $request, TranslatorInterface $translatorInterface): Response
    {
        // Get current user
        $user = $this->userRepository->findOneByEmail($this->getUser()->getUserIdentifier());

        // Create add wallet form
        $addWalletForm = $this->createForm(AddWalletType::class);
        $addWalletForm->handleRequest($request);
        // Add new wallet in db
        if ($addWalletForm->isSubmitted() && $addWalletForm->isValid()) {
            if ($addWalletForm->get('name')->getData() == 'Coinbase') {
                header("Location: https://www.coinbase.com/oauth/authorize?response_type=code&client_id=" . $this->getParameter('oauthcoinbase_key') . "&account=all&scope=wallet:accounts:read");
                exit;
            } else {
                $this->addWallet($addWalletForm);
                return $this->redirect($request->getUri());
            }
        }

        // Add coinbase wallet in database and encrypted data if user give authorisation
        if (isset($_GET['code'])) {
            $wallet = new Wallet();
            $wallet->setAccount($this->getUser())
                ->setName('Coinbase')
                ->setSecretKey('null')
                ->setPassPhrase('null');
            $coinbase = new CoinbaseOauth($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet, $this->httpClientInterface, $this->priceRepository);
            $wallet->setWalletData($coinbase->getBalance($this->getParameter('oauthcoinbase_key'), $this->getParameter('oauthcoinbase_secret'), false, $_GET['code']));
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            $this->addFlash('flash_success', $this->translatorInterface->trans('flashSuccess.addWallet', ['%name%' => $wallet->getName()], 'wallet'));
            return $this->redirectToRoute('wallet');
        }

        // Decrypt all wallet data and get all wallet total value and each wallet total value
        $eachWallet = [];
        $eachWalletTotal = [];
        $allWalletTotal = 0;
        $error = [];
        foreach ($user->getWallet() as $wallet) {
            // decrypt each wallet data
            $decryptedData = Utils::decrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet->getWalletData());
            array_push($eachWallet, [
                'id' => $wallet->getId(),
                'name' => $wallet->getName(),
                'data' => $decryptedData,
            ]);

            // added each coin value and each wallet value for total value
            if ($decryptedData != null) {
                $walletTotal = 0;
                foreach ($decryptedData as $value) {
                    if (!array_key_exists('errorID', $decryptedData)) {
                        $allWalletTotal += $value['value'];
                        $walletTotal += $value['value'];
                    } else {
                        $walletTotal = ['error' => $translatorInterface->trans('error', [], 'wallet')];
                    };
                }
                array_push($eachWalletTotal, $walletTotal);
            }
        }

        return $this->render('main/wallet.html.twig', [
            'AddWalletForm' => $addWalletForm->createView(),
            'data' => $eachWallet,
            'allWalletTotal' => $allWalletTotal,
            'eachWalletTotal' => $eachWalletTotal,
            'errors' => $error,
        ]);
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
            ->setApiKey(Utils::encrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $addWalletForm->get('apiKey')->getData()))
            ->setSecretKey(Utils::encrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $addWalletForm->get('secretKey')->getData()))
            ->setPassPhrase(Utils::encrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $addWalletForm->get('passPhrase')->getData()));

        // Set wallet data relative to selected exchange
        $data = Utils::encrypt(
            $this->getParameter('encryption_key'),
            $this->getParameter('initialization_vector'),
            Utils::apiCall(
                $addWalletForm->get('name')->getData(),
                $addWalletForm->get('apiKey')->getData(),
                $addWalletForm->get('secretKey')->getData(),
                $addWalletForm->get('passPhrase')->getData(),
                $this->priceRepository
            )
        );
        $wallet->setWalletData($data);
        // Add new wallet in database
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        // Update new wallet with total value for each coin
        $name = $wallet->getName();
        $this->addFlash('flash_success', $this->translatorInterface->trans('flashSuccess.addWallet', ['%name%' => $name], 'wallet'));
        dump($data);
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

            $this->addFlash('flash_success', $this->translatorInterface->trans('flashSuccess.removeWallet', ['%name%' => $wallet->getName()], 'wallet'));
        } else {
            $this->addFlash('flash_error', $this->translatorInterface->trans('flashError.removeWallet', [], 'wallet'));
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
        // Update user data column with total value for each coin
        foreach ($user->getWallet() as $wallet) {
            if ($wallet->getName() != 'Coinbase') {
                $data = Utils::encrypt(
                    $this->getParameter('encryption_key'),
                    $this->getParameter('initialization_vector'),
                    Utils::apiCall(
                        $wallet->getName(),
                        Utils::decrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet->getApiKey()),
                        Utils::decrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet->getSecretKey()),
                        Utils::decrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet->getPassPhrase()),
                        $this->priceRepository
                    )
                );
            } else {
                // If wallet == coinbase we need to recall a new access token and refresh token
                $coinbase = new CoinbaseOauth($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet, $this->httpClientInterface, $this->priceRepository);
                $data = $coinbase->getBalance($this->getParameter('oauthcoinbase_key'), $this->getParameter('oauthcoinbase_secret'), true, '');
            }

            $wallet->setWalletData($data);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();
        }
        $this->entityManager->clear();

        // redirect with success message
        $this->addFlash('flash_success', $this->translatorInterface->trans('flashSuccess.priceUpdate', [], 'wallet'));
        return $this->redirectToRoute('wallet');
    }
}
