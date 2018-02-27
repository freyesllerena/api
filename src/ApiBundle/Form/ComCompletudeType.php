<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\TypType;
use ApiBundle\Enum\EnumLabelPeriodType;
use ApiBundle\Validator\Constraints\CheckEmailsList;
use ApiBundle\Validator\Constraints\CheckTypeCodesList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComCompletudeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comUser', null, array('mapped' => false))
            ->add('comLibelle', TextType::class)
            ->add('comPrivee', CheckboxType::class)
            ->add('comAuto', CheckboxType::class)
            ->add('comEmail', null, array('constraints' => new CheckEmailsList()))
            ->add('comPeriode', null, array('empty_data' => EnumLabelPeriodType::DAILY_PERIOD))
            ->add('comAvecDocuments', CheckboxType::class)
            ->add('comPopulation', TextType::class)
            ->add('comNotification', null, array('mapped' => false))
            ->add('comCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('comUpdatedAt', DateTimeType::class, array('mapped' => false))
            ->add('comIdCompletude', null, array('mapped' => false))
            ->add('ctyType', null, array('mapped' => false, 'constraints' => new CheckTypeCodesList(
                ['type' => TypType::TYP_INDIVIDUAL]
            )));

        $builder->get('comEmail')
            ->addModelTransformer(new CallbackTransformer(
                function ($emailsAsString) {
                    return explode(';', $emailsAsString);
                },
                function ($emailsAsArray) {
                    return implode(';', $emailsAsArray);
                }
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\ComCompletude',
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
