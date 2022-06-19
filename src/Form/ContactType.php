<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Nom'
                ),
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Prénom'
                ),
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Email'
                ),
                'constraints' => [new Length(['min' => 2, 'max' =>60])],
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Message'
                ),
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'    => false,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
