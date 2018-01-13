<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actual', PasswordType::class, ['label' => 'Mot de passe actuel'])
            ->add('new', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Répêtér le mot de passe'],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }
}
