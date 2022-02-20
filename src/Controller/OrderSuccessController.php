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
        $order = $em->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        // Check si une commande n'est pas présente et si l'utilisateur connecté est différent du client passant la commande
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //Check si seulement la commande est en statut non payé poru la passer en payé
        if(!$order->getIsPaid()){
            // Modification du status isPaid à OK
            $order->setIsPaid(1);
            //vider le Panier
            $cart->remove();
            $em->flush();

            // envoi de l'email de confirmation de commande pour le client
            $mail = new Mail();
            $content = "Bonjour ".$order->getUser()->getFirstName()."<br/>Un grand merci pour votre commande effectuée sur notre site";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Votre commande DiY District est validée', $content );

        }
        
        
        //affichage du résumé de la commande

        // dd($order);

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
