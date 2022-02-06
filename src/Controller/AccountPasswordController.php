<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ChangePasswordType;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/password-modification", name="account_password")
     */
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;
        $em = $doctrine->getManager();

        $customer= $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $customer);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();

            if ($passwordHasher->isPasswordValid($customer, $old_password)) {
                $new_password = $form->get('new_password')->getData();

                $password = $passwordHasher->hashPassword($customer, $new_password);

                $customer->setPassword($password);
                $em->flush();

                $notification = "Mot de passe mis à jour";
            } else {
                $notification = "Mot de passe actuel invalide";
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
