<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class CommandeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbPers', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'attr' => [
                    'min' => $options['min_persons'],
                    'max' => $options['max_persons'],
                ],
                'constraints' => [
                    new Range(
                        min: $options['min_persons'],
                        max: $options['max_persons'],
                        notInRangeMessage: 'Le nombre de personnes doit être compris entre {{ min }} et {{ max }}.'
                    ),
                ],
            ])
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
            ->add('deliveryDistanceKm', IntegerType::class, [
                'label' => 'Distance depuis Bordeaux (km)',
                'required' => false,
                'help' => 'À remplir uniquement si la livraison n’est pas à Bordeaux.',
                'attr' => [
                    'min' => 0,
                ],
                'constraints' => [
                    new GreaterThanOrEqual(value: 0, message: 'La distance ne peut pas être négative.'),
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
            'min_persons' => 1,
            'max_persons' => null,
        ]);
    }
}
