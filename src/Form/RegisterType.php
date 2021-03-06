<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' =>  false,
                'attr' => array(
                    'placeholder' => 'Nom'
                ),
                'constraints' => [new Length(['min' => 2, 'max' =>30])],
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Prénom'
                ),
                'constraints' => [new Length(['min' => 2, 'max' =>30])],
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Email'
                ),
                'constraints' => [new Length(['min' => 2, 'max' =>60])],
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe doivent être identiques", 
                'required' => true,
                'label' => false,
                'first_options' => [
                    "label" => false,
                    'attr' => array(
                        'placeholder' => 'Mot de passe'
                    ),
                ],
                'second_options' => [
                    "label" => false,
                    'attr' => array(
                        'placeholder' => 'Confirmer votre mot de passe'
                    ),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 's\'inscrire',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
