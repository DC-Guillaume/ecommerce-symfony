<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     */
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher ): Response
    {
        $em = $doctrine->getManager();

        $customer = new Customer();
        $form = $this->createForm(RegisterType::class, $customer);

        //Ecoute du form sur le submit
        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            //hashage du password
            $plaintextPassword = $customer->getPassword();
            $password = $passwordHasher->hashPassword($customer, $plaintextPassword);
            
            $customer->setPassword($password);
            
            // figer la data
            $em->persist($customer);
            // envoi de la data figé
            $em->flush();
        }

        return $this->render('register/index.html.twig', ['form' => $form -> createView()]);
    }
}
