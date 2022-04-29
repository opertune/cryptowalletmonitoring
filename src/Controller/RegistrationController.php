<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Service\Captcha;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translatorInterface): Response
    {
        // Redirect to home page if user is already connect
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $captcha = new Captcha($_POST['g-recaptcha-response']);
            if (!$captcha->captchaIsValid()) {
                // return $this->redirectToRoute('register');
                $this->addFlash('flash_error', $translatorInterface->trans('captcha', [], 'register'));
            } else {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setRoles(array("ROLE_USER"));
                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('cwm@cryptowalletmonitoring.com', 'cwm validator bot'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $this->addFlash('flash_success', $translatorInterface->trans('email.send', [], 'register'));
                return $this->redirectToRoute('register');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('flash_error', $exception->getReason());

            return $this->redirectToRoute('register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        return $this->redirectToRoute('home');
    }
}
