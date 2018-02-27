<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IinIdxIndivType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
		    ->add('iinCodeClient')
            ->add('iinIdCodeSociete')
            ->add('iinIdCodeJalon')
            ->add('iinIdCodeEtablissement')
            ->add('iinIdLibEtablissement')
            ->add('iinIdNomSocietev')
            ->add('iinIdNomClient')
            ->add('iinIdTypePaie')
            ->add('iinIdPeriodePaie')
            ->add('iinIdNomSalarie')
            ->add('iinIdPrenomSalarie')
            ->add('iinIdNomJeuneFilleSalarie')
            ->add('iinIdDateEntree')
            ->add('iinIdDateSortie')
            ->add('iinIdNumNir')
            ->add('iinNumMatricule')
            ->add('iinFichierIndex')
            ->add('iinIdCodeCategProfessionnelle')
            ->add('iinIdCodeCategSocioProf')
            ->add('iinIdTypeContrat')
            ->add('iinIdAffectation1')
            ->add('iinIdAffectation2')
            ->add('iinIdAffectation3')
            ->add('iinIdNumSiren')
            ->add('iinIdNumSiret')
            ->add('iinIdDateNaissanceSalarie')
            ->add('iinIdLibre1')
            ->add('iinIdLibre2')
            ->add('iinIdNumMatriculeGroupe')
            ->add('iinIdNumMatriculeRh')
            ->add('iinIdCodeActivite')
            ->add('iinIdCodeChrono')
            ->add('iinIdDate1')
            ->add('iinIdDate2')
            ->add('iinIdDate3')
            ->add('iinIdDate4')
            ->add('iinIdDate5')
            ->add('iinIdDate6')
            ->add('iinIdDate7')
            ->add('iinIdDate8')
            ->add('iinIdDateAdp1')
            ->add('iinIdDateAdp2')
            ->add('iinIdAlphanum1')
            ->add('iinIdAlphanum2')
            ->add('iinIdAlphanum3')
            ->add('iinIdAlphanum4')
            ->add('iinIdAlphanum5')
            ->add('iinIdAlphanum6')
            ->add('iinIdAlphanum7')
            ->add('iinIdAlphanum8')
            ->add('iinIdAlphanum9')
            ->add('iinIdAlphanum10')
            ->add('iinIdAlphanum11')
            ->add('iinIdAlphanum12')
            ->add('iinIdAlphanum13')
            ->add('iinIdAlphanum14')
            ->add('iinIdAlphanum15')
            ->add('iinIdAlphanum16')
            ->add('iinIdAlphanum17')
            ->add('iinIdAlphanum18')
            ->add('iinIdAlphanumAdp1')
            ->add('iinIdAlphanumAdp2')
            ->add('iinIdNum1')
            ->add('iinIdNum2')
            ->add('iinIdNum3')
            ->add('iinIdNum4')
            ->add('iinIdNum5')
            ->add('iinIdNum6')
            ->add('iinIdNum7')
            ->add('iinIdNum8')
            ->add('iinIdNum9')
            ->add('iinIdNum10')
            ->add('iinIdNumOrdre');
    }
	
	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\IinIdxIndiv',
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