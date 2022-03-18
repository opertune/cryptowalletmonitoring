<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditEmailType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request, UserRepository $ur, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get current user
            $user = $this->getUser();
            // Get current user email
            $userEmail = $ur->findOneByEmail($user->getUserIdentifier());
            // Set current user new email address
            $userEmail->setEmail($form->get('new_email')->getData());
            // Flush db
            $em->flush($userEmail);

            $this->addFlash('flash_success', 'Email change successfully');
            return $this->redirectToRoute('account');
        }

        return $this->render('main/account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
