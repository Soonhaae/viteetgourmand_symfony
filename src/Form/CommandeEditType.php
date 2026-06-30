<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

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
