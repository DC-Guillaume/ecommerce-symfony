<?php

namespace App\Controller;

use App\Classe\MailPassword;
use App\Entity\Customer;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/password-oublie", name="reset_password")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();

        if ($this->getUser()){
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $customer = $em->getRepository(Customer::class)->findOneBy(array('email' => $request->get('email')));

            if($customer){

                // Enregistrement en base des informations de la demande
                $reset_password = new ResetPassword();
                $reset_password->setUser($customer);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime('Europe/Paris'));

                $em->persist($reset_password);
                $em->flush();

                // Envoi d'un email au user avec un lien pour mettre à jour le password
                $mail = new MailPassword();
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);
                $title = "Vous avez oubliez votre password ?";
                $content = "Pas de soucis ". $customer->getLastName() ." " . $customer->getFirstName() ." Voici un lien pour le mettre à jour:";
                $mail->send($customer->getEmail(), $customer->getFirstName(), 'DiY District - Demande de réinitialisation de votre password', $content, $title, $url );

                $this->addFlash('notice_ok', 'Un email de réinitialisation arrive.');
            } else {
                $this->addFlash('notice_error', 'Cette adresse email n\'existe pas.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/password-modification/{token}", name="update_password")
     */
    public function update(ManagerRegistry $doctrine,Request $request, $token, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();

        // Récupération du Token généré
        $reset_password = $em->getRepository(ResetPassword::class)->findOneBy(array('token' => $token));

        // Vérification si le token existe bien, si non redirection vesrs la page reset password
        if (!$reset_password){
            return $this->redirectToRoute('reset_password');
        }

        // Récupération de l'heure actuelle
        $now = new \Datetime('Europe/Paris');

        // Si le token n'est plus valide renvoi à la vue reset_password pour refaire une demande de réinitialisation
        if(!$now > $reset_password->getCreatedAt()->modify('+ 2 hour')){

            $this->addFlash('notice', 'Votre demande de password a expiré. N"hésitez pas à la renouveller.');

            return$this->redirectToRoute('reset_password');
        }

        // Création du form pour réinitialiser le mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        // Lors du submit du form Si il est valide
        if ($form->isSubmitted() && $form->isValid()){

            // Récupération du nouveau mot de passe
            $new_password = $form->get('new_password')->getData();

            // Encodage du mot de pass et flush en base de données
            $password = $passwordHasher->hashPassword($reset_password->getUser(), $new_password);
            $reset_password->getUser()->setPassword($password);
            $em->flush();

            $this->addFlash('notice', 'Votre mail à bien été réinitialisé.');

            return $this->redirectToRoute('app_login');
        }

        return$this->render('reset_password/update.html.twig', [
            'form' => $form -> createView(),
        ]);
    }
}
