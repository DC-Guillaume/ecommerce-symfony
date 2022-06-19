<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart): Response
    {

        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getCart()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_summary", methods={"POST"})
     */
    public function add(ManagerRegistry $doctrine, Cart $cart, Request $request): Response
    {
        $em = $doctrine->getManager();

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $date = new \DateTime('Europe/Paris');
            $delivery = $form->get('delivery')->getData();
            $deliveryAddress = $form->get('addresses')->getData();
            $deliveryAddressDetails = $deliveryAddress->getFirstName(). ' ' .$deliveryAddress->getLastName();
            $deliveryAddressDetails .= '<br/>'.$deliveryAddress->getphone();

            if ( $deliveryAddress->getCompany()){
                $deliveryAddressDetails .= '<br/>'.$deliveryAddress->getCompany();
            }
            $deliveryAddressDetails .= '<br/>'.$deliveryAddress->getAddress();
            $deliveryAddressDetails .= '<br/>'.$deliveryAddress->getPostal().' '.$deliveryAddress->getCity();
            $deliveryAddressDetails .= '<br/>'.$deliveryAddress->getCountry();

            //enregistrer la commande Order
            $order = new Order();
            //ajout d'une référence pour chaques commandes
            $reference = $date->format('dmY').'-'.uniqid();

            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setDeliveryName($delivery->getName());
            $order->setDeliveryPrice($delivery->getPrice());
            $order->setDeliveryAddress($deliveryAddressDetails);
            $order->setState(0);

            $em->persist($order);

            //enregistrer les produits OrderSummary
            foreach($cart->getCart() as $product){
                $orderDetails = new OrderDetails();
                $orderDetails->setCustomerOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $em->persist($orderDetails);
            }

            $em->flush();

            // Ce return ce trouve dans le if car si il n'y a pas de POST associé à cette route la page ne sera pas affichée
            return $this->render('order/add.html.twig', [
                'cart' => $cart->getCart(),
                'delivery' => $delivery,
                'deliveryAddress' => $deliveryAddressDetails,
                // passage de la référence dans l'url pour Stripe
                'reference' => $order->getReference()
            ]);
        }
        // Si il n'y a pas de POST le client sera donc redirigé vers le Cart
        return $this->redirectToRoute('cart');
    }

}
