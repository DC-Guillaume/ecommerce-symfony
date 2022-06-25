<?php

namespace App\Controller\StaticPages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolitiqueController extends AbstractController
{
    /**
     * @Route("/politique-de-confidentialite", name="politique")
     */
    public function index(): Response
    {
        return $this->render('static_pages/politique.html.twig');
    }
}
