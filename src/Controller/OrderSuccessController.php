<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    /**
     * @Route("/commande/success/{stripeSessionId}", name="order_success")
     */
    public function index(ManagerRegistry $doctrine, Cart $cart, $stripeSessionId): Response
    {
        $em = $doctrine->getManager();
        // Acquisition de l'id unique de la session stripe liée à cette commande
        $order = $em->getRepository(Order::class)->findOneBy(array('stripeSessionId' => $stripeSessionId));

        // Check si une commande n'est pas présente et si l'utilisateur connecté est différent du client passant la commande
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //Check si seulement la commande est en statut non payée pour la passer en statut payée dans EasyAdmin
        if( $order->getState() == 0 ){
            // Modification du status isPaid à OK
            $order->setState(1);
            //vider le Panier
            $cart->remove();
            $em->flush();

            // Envoi de l'email de confirmation de commande pour le client
            $mail = new Mail();
            $title = "Un GRAND merci !";
            $content = "Bonjour ". $order->getUser()->getLastName() ." " . $order->getUser()->getFirstName() ."<br/><br/>Votre confiance nous fait extremement plaisir. Nous allons préparer votre commande au plus vite.";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'DiY District - Votre commande est validée', $content, $title );
        }

        //Affichage du résumé de la commande
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
