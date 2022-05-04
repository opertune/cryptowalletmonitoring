<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap", name="sitemap")
     */
    public function index(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $urls = [
            ['loc' => $this->generateUrl('home')],
            ['loc' => $this->generateUrl('question')],
            ['loc' => $this->generateUrl('contact')],
            ['loc' => $this->generateUrl('account')],
            ['loc' => $this->generateUrl('wallet')],
            ['loc' => $this->generateUrl('register')],
            ['loc' => $this->generateUrl('login')],
        ];
        $response = new Response(
            $this->renderView('main/sitemap.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
