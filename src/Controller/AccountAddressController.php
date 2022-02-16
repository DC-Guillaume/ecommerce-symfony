<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Customer;
use App\Form\AddressType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/compte/adresses", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-adresse", name="account_address_add")
     */
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $address->setCustomer($this->getUser());

            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-adresse/{id}", name="account_address_edit")
     */
    public function edit(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $em = $doctrine->getManager();

        $address = $em->getRepository(Address::class)->findOneBy(['id' => $id]);

        if (!$address || $address->getCustomer() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();

            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer-adresse/{id}", name="account_address_delete")
     */
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();

        $address = $em->getRepository(Address::class)->findOneBy(['id' => $id]);

        if ($address && $address->getCustomer() == $this->getUser()) {
            $em->remove($address);
            $em->flush();
        }
            
        

        return $this->redirectToRoute('account_address');

    }
}
