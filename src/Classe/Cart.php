<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart {

    private $request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->request = $requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        // Je stock la session actuelle du panier dans la variable cart qui renvoie un tableau
        $cart = $this->request->get('cart', []);

        // Si le panier a bien un produit déjà existant
        if (!empty($cart[$id])){
            // Alors je rajoute une quantity
            $cart[$id]++;

        }else {
            $cart[$id] = 1;
        }

        $this->request->set('cart', $cart);

    }

    public function get()
    {

        return $this->request->get('cart');
    }

    public function getCart()
    {
        $cartSummary = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
                if (!$product){
                    $this->delete($id);
                    continue;
                }
                $cartSummary[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                
            }
        }
        return $cartSummary;
    }

    public function remove()
    {

        return $this->request->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->request->get('cart', []);

        unset($cart[$id]);

        return $this->request->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->request->get('cart', []);

        if($cart[$id] >1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->request->set('cart', $cart);
    }
}
