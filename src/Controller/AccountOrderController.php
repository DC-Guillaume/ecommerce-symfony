<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    /**
     * @Route("/compte/commandes", name="account_order")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $orders = $em->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/compte/commandes/{reference}", name="account_order_show")
     */
    public function show(ManagerRegistry $doctrine, $reference): Response
    {
        $em = $doctrine->getManager();
        $order = $em->getRepository(Order::class)->findOneByReference($reference);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/orderShow.html.twig', [
            'order' => $order,
        ]);
    }
}
