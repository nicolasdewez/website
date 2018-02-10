<?php

namespace App\Form;

use App\Model\BalanceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BalanceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year', ChoiceType::class, [
                'label' => 'Année',
                'choices' => $this->getYears(),
            ])
        ;
    }

    private function getYears(): array
    {
        $years = [];
        for ($i = 2018; $i <= date('Y'); ++$i) {
            $years[$i] = $i;
        }

        return $years;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BalanceSearch::class,
        ]);
    }
}
