<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    /**
     * Page for showing all wallet information
     * @Route("/wallet", name="wallet")
     */
    public function index(): Response
    {
        return $this->render('main/wallet.html.twig', []);
    }


    /**
     * Page for showing more information from selected wallet by exchange
     * @Route("/wallet/{id}", name="byexchange")
     */
    public function byExchange(): Response
    {
        return $this->render('main/walletByExchange.html.twig', [
            'title' => 'binance',
            'exchange' => 'binance'
        ]);
    }
}