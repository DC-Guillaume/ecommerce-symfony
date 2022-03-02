<?php

namespace App\Controller;

use App\Classe\Mail;
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
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;
        $em = $doctrine->getManager();

        $customer = new Customer();
        $form = $this->createForm(RegisterType::class, $customer);

        //Ecoute du form sur le submit
        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            //Check si le customer n'existe pas déjà
            $searchEmail = $em->getRepository(Customer::class)->findOneBy(array('email' => $customer->getEmail()));

            if(!$searchEmail){

                //Hashage du password
                $plaintextPassword = $customer->getPassword();
                $password = $passwordHasher->hashPassword($customer, $plaintextPassword);
                $customer->setPassword($password);

                // Fige la data
                $em->persist($customer);
                // Envoi de la data figé
                $em->flush();
                $notification ='Votre inscription est finalisée, vous pouvez vous connecter';

                //Envoi du mail de confirmation via MailJet
                $mail = new Mail();
                $content = "Bonjour ".$customer->getFirstName()."<br/>Bienvenue sur notre boutique en ligne de tissus Japonais";
                $mail->send($customer->getEmail(), $customer->getFirstName(), 'Bienvenue sur DiY District', $content );

            }else {
                $notification ="L'email que vous avez renseigné existe déjà";
            
            
            
            }
        }

        return $this->render('register/index.html.twig', [
        'form' => $form -> createView(),
        'notification' => $notification,
        ]);
    }
}