<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\AddWalletType;
use App\Repository\PriceRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $addWalletForm = $this->createForm(AddWalletType::class);
        $addWalletForm->handleRequest($request);

        // Add new wallet in db
        if ($addWalletForm->isSubmitted() && $addWalletForm->isValid()) {
            $this->addWallet($addWalletForm, $this->priceRepository);
            return $this->redirect($request->getUri());
        }

        // Decrypt all wallet data and get all wallet total value and each wallet total value
        $eachWallet = [];
        $eachWalletTotal = [];
        $allWalletTotal = 0;

        foreach ($user->getWallet() as $wallet) {
            // decrypt each wallet data
            $decryptedData = Utils::decrypt($this->getParameter('encryption_key'), $this->getParameter('initialization_vector'), $wallet->getWalletData());
            array_push($eachWallet, [
                'id' => $wallet->getId(),
                'name' => $wallet->getName(),
                'data' => $decryptedData,
            ]);

            // added each coin value and each wallet value for total value
            $walletTotal = 0;
            foreach ($decryptedData as $value) {
                $allWalletTotal += $value['value'];
                $walletTotal += $value['value'];
            }
            array_push($eachWalletTotal, $walletTotal);
        }

        return $this->render('main/wallet.html.twig', [
            'AddWalletForm' => $addWalletForm->createView(),
            'data' => $eachWallet,
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
        // Update user data column with total value for each coin
        foreach ($user->getWallet() as $wallet) {
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

            $wallet->setWalletData($data);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();
        }
        $this->entityManager->clear();

        // redirect with success message
        $this->addFlash('flash_success', 'Price updated successfully');
        return $this->redirectToRoute('wallet');
    }
}


// Faire en sorte que getallcoinsprice se déclanche tout les x temps sans que l'utilisateur ai à le faire de lui même
// Utilisation de crontab sur linux à configurer au moment du passage sur un hebergeur.

// Commencer le front