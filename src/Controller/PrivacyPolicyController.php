<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivacyPolicyController extends AbstractController
{
    /**
     * @Route("/privacy-policy", name="privacyPolicy")
     */
    public function index(): Response
    {
        return $this->render('main/privacyPolicy.html.twig', []);
    }
}
