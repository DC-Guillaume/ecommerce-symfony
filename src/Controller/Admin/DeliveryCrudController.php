<?php

namespace App\Controller\Admin;

use App\Entity\Delivery;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DeliveryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Delivery::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Livreur'),
            TextareaField::new('description'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
        ];
    }
    
}
