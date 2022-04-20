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
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request, UserPasswordHasherInterface $hasher, UserRepository $userRepository, EntityManagerInterface $entityManager, TranslatorInterface $translatorInterface): Response
    {
        // Get current user by email
        $user = $userRepository->findOneByEmail($this->getUser()->getUserIdentifier());

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
                $this->addFlash('flash_success', $translatorInterface->trans('editPassword.success', [], 'account'));
            } else {
                $this->addFlash('flash_error', $translatorInterface->trans('editPassword.error', [], 'account'));
            }
        }

        return $this->render('main/account.html.twig', [
            'formEditPW' => $formEditPW->createView(),
        ]);
    }
}
