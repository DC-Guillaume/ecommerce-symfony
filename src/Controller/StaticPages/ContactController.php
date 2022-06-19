<?php

namespace App\Controller\StaticPages;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $contact->setCreatedAt(new \DateTime('Europe/Paris'));

            $contact = $form->getData();

            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice_contact_form', 'Votre message a été envoyé, nous vous repondrons dans les plus brefs délais');

            return $this->redirectToRoute('contact');
        }

        return $this->render('static_pages/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
