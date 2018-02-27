<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapRapportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rapTypeRapport')
            ->add('rapFichier', TextType::class)
            ->add('rapLibelleFic', TextType::class)
            ->add('rapEtat', TextType::class)
            ->add('rapCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('rapUpdatedAt', DateTimeType::class, array('mapped' => false))
            ->add('rapUser');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\RapRapport',
            'csrf_protection' => false
        ));
    }
}
