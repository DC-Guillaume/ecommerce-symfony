<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Nom de votre adresse'
                ),
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Prénom'
                ),
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Nom'
                ),
            ])
            ->add('company', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Société'
                ),
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Adresse'
                ),
            ])
            ->add('postal', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Code postal'
                ),
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Ville'
                ),
            ])
            ->add('country', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Pays'
                ),
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Téléphone'
                ),
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider l'adresse",
                'attr' => [
                    'class' => 'btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
