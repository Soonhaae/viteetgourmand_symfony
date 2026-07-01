<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'label' => 'Note',
                'choices' => [
                    '5 - excellent' => 5,
                    '4 - très bien' => 4,
                    '3 - bien' => 3,
                    '2 - moyen' => 2,
                    '1 - décevant' => 1,
                ],
                'placeholder' => 'choisir une note',
                'constraints' => [
                    new NotBlank(message: 'Veuillez choisir une note.'),
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'rows' => 4,
                    'maxlength' => 1000,
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez écrire un commentaire.'),
                    new Length(max: 1000, maxMessage: 'Le commentaire ne doit pas dépasser {{ limit }} caractères.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
