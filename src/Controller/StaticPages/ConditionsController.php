<?php

namespace App\Controller\StaticPages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
    /**
     * @Route("/conditions-generales-de-vente", name="conditions")
     */
    public function index(): Response
    {
        return $this->render('static_pages/conditions.html.twig');
    }
}
