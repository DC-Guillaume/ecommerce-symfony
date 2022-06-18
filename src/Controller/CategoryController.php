<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie/{slug}", name="categories")
     */
    public function categoryProducts(ManagerRegistry $doctrine, $slug, Category $category): Response
    {
        
        $em = $doctrine->getManager();
        $categories = $em->getRepository(Category::class)->findOneBy(['slug' => $slug]);
        $products = $em->getRepository(Product::class)->findBy(array('category' => $category));

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
