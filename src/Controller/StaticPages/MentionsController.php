<?php

namespace App\Controller\StaticPages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    /**
     * @Route("/mentions-complementaires", name="mentions")
     */
    public function index(): Response
    {
        return $this->render('static_pages/mentions.html.twig');
    }
}
