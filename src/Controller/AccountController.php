<?php

namespace App\Controller;

use App\Form\EditEmailType;
use App\Form\EditPassWordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request, UserPasswordHasherInterface $hasher, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Get current user by email
        $user = $userRepository->findOneByEmail($this->getUser()->getUserIdentifier());

        // Form for edit email account
        $formEditEmail = $this->createForm(EditEmailType::class);
        $formEditEmail->handleRequest($request);

        // Edit email account
        if ($formEditEmail->isSubmitted() && $formEditEmail->isValid()) {
            // Set current user new email address
            $user->setEmail($formEditEmail->get('new_email')->getData());
            $entityManager->flush();

            $this->addFlash('flash_success', 'Email change successfully');
            return $this->redirectToRoute('account');
        }

        // Form for edit password
        $formEditPW = $this->createForm(EditPassWordType::class);
        $formEditPW->handleRequest($request);

        // Edit password account
        if ($formEditPW->isSubmitted() && $formEditPW->isValid()) {
            if ($hasher->isPasswordValid($this->getUser(), $formEditPW->get('old_password')->getData())) {
                // Encode new password
                $encodedPassword = $hasher->hashPassword(
                    $user,
                    $formEditPW->get('new_password')->getData()
                );
                // Set current user new password
                $user->setPassword($encodedPassword);
                $entityManager->flush();
                $entityManager->clear();
                $this->addFlash('flash_success', 'Old password successfully edited');
            } else {
                $this->addFlash('flash_error', 'Invalid old password');
            }
        }

        return $this->render('main/account.html.twig', [
            'formEditEmail' => $formEditEmail->createView(),
            'formEditPW' => $formEditPW->createView(),
        ]);
    }
}
