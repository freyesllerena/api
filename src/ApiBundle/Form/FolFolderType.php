<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolFolderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('folId', null, array('mapped' => false))
            ->add('folLibelle', TextType::class)
            ->add('folIdOwner', null, array('mapped' => false))
            ->add('folNbDoc', null, array('mapped' => false))
            ->add('folCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('folUpdatedAt', DateTimeType::class, array('mapped' => false));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\FolFolder',
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
