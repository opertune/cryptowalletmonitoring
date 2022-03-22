<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\addWallet;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $wallet = new Wallet();
        $wallet->setAccount($this->getUser())
            ->setName($addWalletForm->get('name')->getData())
            ->setApiKey($addWalletForm->get('apiKey')->getData())
            ->setSecretKey($addWalletForm->get('secretKey')->getData());
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();
        $this->addFlash('flash_success', 'Wallet successfully added');
        return $this->redirectToRoute('wallet');
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
}
