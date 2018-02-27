<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\FolFolder;
use ApiBundle\Form\DataTransformer\FolFolderToNumberTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdoAnnotationsDossierType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
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
            ->add('adoEtat', null, array('mapped' => false))
            ->add('adoStatut', TextType::class)
            ->add('adoTexte', TextType::class)
            ->add('adoCreatedAt', DateTimeType::class, array('mapped' => false))
            ->add('adoUpdatedAt', DateTimeType::class, array('mapped' => false))
            ->add('adoFolder', null, array('invalid_message' => FolFolder::ERR_DOES_NOT_EXIST))
            ->add('adoLogin', null, array('mapped' => false))
            ->add('adoId', null, array('mapped' => false));

        $builder->get('adoFolder')
            ->addModelTransformer(new FolFolderToNumberTransformer($this->entityManager));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\AdoAnnotationsDossier',
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
