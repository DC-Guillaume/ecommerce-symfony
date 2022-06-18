<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classe\Cart;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(ManagerRegistry $doctrine, Cart $cart): Response
    {
        $em = $doctrine->getManager();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add($id, Cart $cart): Response
    {
        $cart->add($id);
        
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_from_cart")
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_from_cart")
     */
    public function delete($id, Cart $cart): Response
    {
        $cart->delete($id);
        
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="decrease_from_cart")
     */
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);
        
        return $this->redirectToRoute('cart');
    }


}
