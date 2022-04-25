<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\Captcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, TranslatorInterface $translatorInterface): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $captcha = new Captcha($_POST['g-recaptcha-response']);
            if ($captcha->captchaIsValid()) {
                $this->addFlash('flash_success', $translatorInterface->trans('success', [], 'contact'));
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
