<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\TypType;
use ApiBundle\Validator\Constraints\CheckTypeCodesList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProProcessusType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'proGroupe',
                IntegerType::class,
                array('invalid_message' => 'errDocapostControllerParameterTypeIsNotAnInteger')
            )
            ->add('proLibelle', TextType::class)
            ->add('proCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('proUpdatedAt', DateTimeType::class, array('mapped' => false))
            ->add('proUser', null, array('mapped' => false))
            ->add('proProcessus', null, array('mapped' => false))
            ->add('ptyType', null, array('mapped' => false, 'constraints' => new CheckTypeCodesList(
                ['type' => TypType::TYP_MIXED]
            )))
            ->add('proId', null, array('mapped' => false));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\ProProcessus',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
