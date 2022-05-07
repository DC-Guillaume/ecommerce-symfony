<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du slider'),
            TextareaField::new('content', 'Sous titre du slider'),
            TextField::new('btnTitle', 'Texte du bouton'),
            TextField::new('btnUrl', 'Url de destination du bouton'),
            ImageField::new('illustration', 'Illustration du Slider')
            ->setBasePath('uploads/products')
            ->setUploadDir('public/uploads/products')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ];
    }
}
