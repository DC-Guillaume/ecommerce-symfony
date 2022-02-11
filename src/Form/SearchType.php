<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un produit',
                    'class' => 'search-input'
                ]
                
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => false,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-filter'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            // Données qui transite par l'url pour permettre aux utilisateurs de copier/coller les url et de les partager
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    // Permet de retourner un tableau avec les valeurs sans prefix "search" 
    public function getBlockPrefix()
    {
        return '';
    }
}