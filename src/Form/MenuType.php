<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Regime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('theme')
            ->add('regime')
            ->add('content')
            ->add('minPersons')
            ->add('price')
            ->add('conditions')
            ->add('status')
            ->add('plats', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => 'id',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('regimes', EntityType::class, [
                'class' => Regime::class,
                'choice_label' => 'id',
                'multiple' => true,
                'expanded' => true,
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
