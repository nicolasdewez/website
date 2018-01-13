<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Libellé'])
            ->add('writingDate', DateType::class, [
                'label' => 'Date d\'écriture (YYY-mm-dd)',
                'widget' => 'single_text',
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publié',
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'title',
                'multiple' => true,
            ])
            ->add('body', TextareaType::class, ['label' => 'Contenu'])
        ;
    }
}
