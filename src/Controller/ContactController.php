<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\Captcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, TranslatorInterface $translatorInterface, MailerInterface $mailerInterface): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $captcha = new Captcha($_POST['g-recaptcha-response']);
            if ($captcha->captchaIsValid()) {
                $email = (new Email())
                    ->from(new Address($form->get('email')->getData(), $form->get('email')->getData()))
                    ->to('cwm@cryptowalletmonitoring.com')
                    ->subject('Contact form new message')
                    ->text($form->get('message')->getData());
                $mailerInterface->send($email);
                $this->addFlash('flash_success', $translatorInterface->trans('success', [], 'contact'));
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
