<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $products = $em->getRepository(Product::class)->findAll();

        // Passer le formulaire au twig
        // Class Search
        $search = new Search();
        // Appel du formulaire SearchType
        $form = $this->createForm(SearchType::class, $search);
        // Ecoute sur le form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $products = $em->getRepository(Product::class)->findBySearch($search);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            //Creation de la vue search
            'form' => $form -> createView(),

        ]);
    }

    /**
     * @Route("/product/{slug}", name="product")
     */
    public function productShow(ManagerRegistry $doctrine, $slug): Response
    {

        $em = $doctrine->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/productShow.html.twig', [
            'product' => $product
        ]);
    }
}
