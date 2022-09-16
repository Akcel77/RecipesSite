<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la recette : ',
                'attr' => [
                    'placeholder' => 'Raviolis, Boeuf Bourguignon ...',
                ]
            ])
            ->add('time', IntegerType::class, [
                'label' => 'Temps total de préparation en minutes:',
                'attr' => [
                    'placeholder' => '90, 25 ...',
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'label' => 'Recette pour combien de personne :',
                'attr' => [
                    'placeholder' => '2, 4 ...',
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'label' => 'Difficulté sur 5 ',
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descriptif de la recette : ',
                'attr' => [
                    'placeholder' => 'Dis nous en d\'avantage sur ta recette',
                ]
            ])
            ->add('price',MoneyType::class, [
                'label' => 'Prix de la recette : ',
                'attr' => [
                    'placeholder' => 'Veuillez saisir le prix votre recette',
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'label' => 'Recette Favorite ?'
            ])
            ->add('ingredients',EntityType::class, [
                'class' => Ingredient::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Ajouter les ingredients',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-outline-light',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
