<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Slider;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        // Gestion des produits mis en avant sur la page principale du site
        $products = $em->getRepository(Product::class)->findBy(array('highlight' => 1));
        $sliders = $em->getRepository(Slider::class)->findAll();


        return $this->render('home/index.html.twig', [
            'products' => $products,
            'sliders' => $sliders,
        ]);
    }
}
