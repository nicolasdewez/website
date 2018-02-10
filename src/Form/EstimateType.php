<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Estimate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer', EntityType::class, [
                'label' => 'Client',
                'class' => Customer::class,
                'choice_label' => function (Customer $customer) {
                    return $customer->getDisplayName();
                },
            ])
            ->add('date', DateType::class, [
                'label' => 'Date (YYYY-mm-dd)',
                'widget' => 'single_text',
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date d\'échéance (YYYY-mm-dd)',
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire / Remarque',
                'required' => false,
            ])
            ->add('lines', CollectionType::class, [
                'label' => 'Lignes',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => EstimateLineType::class,
            ])
            ->add('total', TextType::class, [
                'label' => 'Total',
                'required' => false,
                'mapped' => false,
                'attr' => ['disabled' => true],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estimate::class,
        ]);
    }
}
