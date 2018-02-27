<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Form\DataTransformer\IfpIndexfichePaperlessToNumberTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnoAnnotationsType extends AbstractType
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anoEtat', null, array('mapped' => false))
            ->add('anoStatut', TextType::class)
            ->add('anoTexte', TextType::class)
            ->add('anoCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('anoUpdatedAt', DateTimeType::class, array('mapped' => false))
            ->add('anoFiche', null, array('invalid_message' => IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED))
            ->add('anoLogin', null, array('mapped' => false))
            ->add('anoId', null, array('mapped' => false));

        $builder->get('anoFiche')
            ->addModelTransformer(new IfpIndexfichePaperlessToNumberTransformer($this->entityManager));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\AnoAnnotations',
            'csrf_protection' => false,
            'allow_extra_fields' => true
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
