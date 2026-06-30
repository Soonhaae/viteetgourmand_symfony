<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\IsTrue;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbPers', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'attr' => [
                    'min' => $options['min_persons'],
                ],
                'constraints' => [
                    new GreaterThanOrEqual(
                        value: $options['min_persons'],
                        message: 'Le nombre de personnes doit être supérieur ou égal à {{ compared_value }}.'
                    ),
                ],
            ])
            ->add('conditionsAccepted', CheckboxType::class, [
                'label' => 'J’ai bien pris connaissance et j’accepte les conditions de ce menu',
                'required' => true,
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter les conditions pour commander.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'min_persons' => 1,
        ]);
    }
}
