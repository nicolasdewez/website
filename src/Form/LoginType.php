<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', null, ['label' => 'Nom d\'utilisateur'])
            ->add('_password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('submit', SubmitType::class, ['label' => 'Se connecter', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
