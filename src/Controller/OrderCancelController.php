<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    /**
     * @Route("commande/cancel/{stripeSessionId}", name="order_cancel")
     */
    public function index(ManagerRegistry $doctrine, $stripeSessionId): Response
    {
        $em = $doctrine->getManager();
        $order = $em->getRepository(Order::class)->findOneBy(array('stripeSessionId' => $stripeSessionId));

        // Si le user est différent de celui qui à effectué la commande, redirection vers la page Home
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //Envoi d'un email pour indiquer l'échec de paiement.

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order,
        ]);
    }
}
