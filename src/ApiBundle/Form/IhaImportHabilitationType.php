<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IhaImportHabilitationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ihaTraite', IntegerType::class)
            ->add('ihaSucces', IntegerType::class)
            ->add('ihaErreur', IntegerType::class)
            ->add('ihaCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('ihaUpdatedAt', DateTimeType::class, array('mapped' => false))
            ->add('ihaRapport', new RapRapportType());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\IhaImportHabilitation',
            'csrf_protection' => false
        ));
    }
}
