<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuideController extends AbstractController
{
    /**
     * @Route("/guide", name="guide")
     */
    public function index(): Response
    {
        return $this->render('main/guide.html.twig', []);
    }
}
