<?php

namespace App\Form;

use App\Entity\BillLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Libellé'])
            ->add('quantity', NumberType::class, ['label' => 'Quantité'])
            ->add('unitPrice', NumberType::class, ['label' => 'Prix unitaire'])
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
            'data_class' => BillLine::class,
        ]);
    }
}
