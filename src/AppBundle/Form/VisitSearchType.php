<?php

namespace AppBundle\Form;

use AppBundle\Entity\Visit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class VisitSearchType.
 */
class VisitSearchType extends AbstractType
{
    const GROUP_DATE = 'date';
    const GROUP_URL = 'path';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DateType::class, ['label' => 'Date de début'])
            ->add('end', DateType::class, ['label' => 'Date de fin'])
            ->add('path', TextType::class, ['label' => 'Url', 'required' => false])
            ->add('group', ChoiceType::class, [
                'label' => 'Grouper par',
                'required' => false,
                'choices'  => [
                    'Date' => self::GROUP_DATE,
                    'Url' => self::GROUP_URL,
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Rechercher', 'attr' => ['class' => 'btn btn-success']])
        ;
    }
}
