<?php

namespace App\Controller;

use App\Service\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContactController extends AbstractController
{
    // private $client;

    // public function __construct(HttpClientInterface $client)
    // {
    //     $this->client = $client;
    // }

    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {
        // $formCoinbase = $this->createFormBuilder()
        //     ->add('button', SubmitType::class, [
        //         'row_attr' => [
        //             'name' => 'coinbaseOauth',
        //             'id' => 'coinbaseOauth',
        //             'class' => 'form-floating'
        //         ],
        //         'attr' => [
        //             'class' => 'w-100 btn btn-lg btn-outline-primary mb-2'
        //         ],
        //         'label' => 'Coinbase'
        //     ])
        //     ->getForm();

        // $formCoinbase->handleRequest($request);

        // if ($formCoinbase->isSubmitted() && $formCoinbase->isValid()) {
        //     header('Location: https://www.coinbase.com/oauth/authorize?response_type=code&client_id=7a579cdeb47df9088f9b853e7f40a3ed0a6805ff4918f35027b62326a51a0361&state=134ef5504a94&account=all&scope=wallet:accounts:read');
        //     exit;
        // }

        // if (isset($_GET['code'])) {
        //     $code = $_GET['code'];
        //     $rep = $this->httpClientInterface->request(
        //         'POST',
        //         "https://api.coinbase.com/oauth/token?grant_type=authorization_code&code=$code&client_id=7a579cdeb47df9088f9b853e7f40a3ed0a6805ff4918f35027b62326a51a0361&client_secret=aafb132ce7b3d0b9bf1e13abc116a0ad625d08e622376b62546c2e3ea39f6ef2&redirect_uri=https://127.0.0.1:8000/en/wallet"
        //     );
        //     $cont = json_decode($rep->getContent(), true);
        //     $bearer = $cont["access_token"];
        //     $coinbase = [];
        //     $coins = [];
        //     $next = '';
        //     do {
        //         $coinbase = Utils::curlRequest("https://api.coinbase.com/v2/accounts?limit=100&order=asc&next_uri=$next", ["Authorization: Bearer $bearer", "CB-VERSION: 2015-04-08"]);
        //         $next = $coinbase['pagination']['next_uri'];
        //         foreach ($coinbase['data'] as $currency) {
        //             if ($currency['balance']['amount'] > 0.000) {
        //                 array_push($coins, array(
        //                     'symbol' => $currency['balance']['currency'],
        //                     'quantity' => $currency['balance']['amount'],
        //                 ));
        //             }
        //         }
        //     } while ($coinbase['pagination']['next_uri'] != null);

        //     dd($cont);
        // }

        return $this->render('main/contact.html.twig', [
            // 'formCoinbase' => $formCoinbase->createView(),
        ]);
    }
}

// {{ form(formCoinbase) }}
