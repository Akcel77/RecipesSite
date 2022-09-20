<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fisrtname', TextType::class, [
                'label' => 'Votre prénom :',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 55,
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prenom',
                ]
            ])
            ->add('name',TextType::class, [
                'label' => 'Votre nom :',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30,
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom',
                ]
            ])->add('pseudo',TextType::class, [
                'label' => 'Votre pseudo :',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30,
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre pseudo',
                ]
            ])
            ->add('email',EmailType::class, [
                'label' => 'Votre email :',
                'constraints' => new Length([
                    'min' => 5,
                    'max' => 60,
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre email',
                ]
            ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent etre identique',
                'constraints' => new Length([
                    'min' => 8,
                    'max' => 30,
                ]),
                'label' => 'Votre mot de passe :',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe',
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe',
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
