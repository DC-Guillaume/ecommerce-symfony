<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Pour classer les commandes par ordre descendant
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //Gestion des fiels coté EasyAdmin pour la partie Orders
            // IdField::new('ID', 'N°'),
            TextField::new('reference', 'Commande n°'),
            DateTimeField::new('createdAt', 'Date'),
            TextField::new('user.getFullName', 'Client'),
            TextField::new('deliveryName', 'Transporteur'),
            MoneyField::new('total')->setCurrency('EUR'),
            MoneyField::new('DeliveryPrice', 'Fraid de Port')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payée'),
            ArrayField::new('orderDetails','Produits')->hideOnIndex(),
        ];
    }
    
}
