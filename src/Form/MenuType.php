<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Regime;
use App\Enum\MenuStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
            ])
            ->add('theme', null, [
                'label' => 'Thème',
            ])
            ->add('content', null, [
                'label' => 'Description',
            ])
            ->add('minPersons', IntegerType::class, [
                'label' => 'Nombre minimum de personnes',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR',
            ])
            ->add('conditions', null, [
                'label' => 'Conditions',
            ])
            ->add('plats', EntityType::class, [
                'label' => 'Plats',
                'class' => Plat::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('regimes', EntityType::class, [
                'label' => 'Régimes',
                'class' => Regime::class,
                'choice_label' => fn (Regime $regime): string => match ($regime->getName()) {
                    'vegetarien' => 'végétarien',
                    default => (string) $regime->getName(),
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('regime', TextareaType::class, [
                'label' => 'Infos suppl.',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                ],
            ])
            ->add('status', EnumType::class, [  /* J'ajoute un champ nommé "status", qui correspond à la propriété de mon entité Menu, donc symfony devra lire et modifier cette propriété*/
                /* EnumType::class = 2e argiument = le type de champ qu'il faut utiliser (le champ est une "énumération PHP" = "enum"*/
                /* Ensuite dans le tableau [], c'est les options du champ */
                'label' => 'Statut',
                'class' => MenuStatus::class,                                           /* 1/ L'enum à utiliser est MenuStatus*/
                'choice_label' => fn(MenuStatus $status): string => $status->label(),   /* 2/ La fonction pour obtenir le nom écrit (string) de chaque label (fn = fonction fléchée !)*/
                'placeholder' => false,                                                 /* 3/ Pas de valeur vide par défaut (= oblig de choisir une des valeurs de l'énum*/
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
