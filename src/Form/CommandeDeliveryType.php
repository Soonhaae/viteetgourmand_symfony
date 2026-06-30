<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CommandeDeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prestationDate', DateType::class, [
                'label' => 'Date de prestation',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank(message: 'Veuillez indiquer la date de prestation.'),
                ],
            ])
            ->add('prestationTime', TimeType::class, [
                'label' => 'Heure souhaitée',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank(message: 'Veuillez indiquer une heure souhaitée.'),
                ],
            ])
            ->add('deliveryAddress', TextType::class, [
                'label' => 'Adresse de livraison',
                'attr' => [
                    'autocomplete' => 'street-address',
                    'maxlength' => 255,
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez indiquer une adresse de livraison.'),
                    new Length(max: 255, maxMessage: 'L’adresse ne doit pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('deliveryPostalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'autocomplete' => 'postal-code',
                    'maxlength' => 5,
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez indiquer un code postal.'),
                    new Regex(pattern: '/^\d{5}$/', message: 'Le code postal doit contenir 5 chiffres.'),
                ],
            ])
            ->add('deliveryCity', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'autocomplete' => 'address-level2',
                    'maxlength' => 100,
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez indiquer une ville.'),
                    new Length(max: 100, maxMessage: 'La ville ne doit pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('deliveryDetails', TextareaType::class, [
                'label' => 'Précisions de livraison',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'maxlength' => 500,
                ],
                'constraints' => [
                    new Length(max: 500, maxMessage: 'Les précisions ne doivent pas dépasser {{ limit }} caractères.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
