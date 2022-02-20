<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {
        $productsForStripe = [];
        $YOUR_DOMAIN = 'http://diydistrict.fr';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        // Si il ne trouve pas de commande retour à la page order
        if (!$order){
            new JsonResponse(['error' => 'order']);
        }
        

        foreach($order->getOrderDetails()->getValues() as $product){
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $productsForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/products/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $productsForStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getDeliveryPrice(),
                'product_data' => [
                    'name' => $order->getDeliveryName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51KLTsfFXZ3JDEwquLfxxaSwzVAZnqGvLidYRTDZAPlYHta2gmEFtTZFzRbl3B2nCX7w646N6kD7EZbGeWYd88HmA00LjsTgX18');

        // La Session Create génère un id pour stripe
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $productsForStripe
            ],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            // Nous passons l'id stripe dans l'url
            'success_url' => $YOUR_DOMAIN . '/commande/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/cancel/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        // Etant donné que l'objet est déjà créer nous effectuons un flush sans passer par le persist
        $entityManager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
