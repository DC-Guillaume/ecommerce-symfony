<?php

namespace App\Controller\StaticPages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/about-us", name="about-us")
     */
    public function index(): Response
    {
        return $this->render('static_pages/about.html.twig');
    }
}
