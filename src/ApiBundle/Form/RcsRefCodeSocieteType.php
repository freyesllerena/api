<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RcsRefCodeSocieteType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
			->add('libelle')
			->add('idCodeClient')
			->add('siren')
			;
    }
	
	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\RceRefCodeEtablissement',
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