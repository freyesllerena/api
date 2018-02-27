<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\TypType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IfpIndexfichePaperlessType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ifpDocumentsassocies')
            ->add('ifpVdmLocalisation')
            ->add('ifpRefpapier')
            ->add('ifpNombrepages')
            ->add('ifpIdCodeChrono')
            ->add('ifpIdNumeroBoiteArchive')
            ->add('ifpInterbox')
            ->add('ifpLotIndex')
            ->add('ifpLotProduction')
            ->add('ifpIdNomSociete')
            ->add('ifpIdCompany')
            ->add('ifpIdNomClient')
            ->add('ifpIdCodeSociete')
            ->add('ifpIdCodeEtablissement')
            ->add('ifpIdLibEtablissement')
            ->add('ifpIdCodeJalon')
            ->add('ifpIdLibelleJalon')
            ->add('ifpIdNumSiren')
            ->add('ifpIdNumSiret')
            ->add('ifpIdIndiceClassement')
            ->add('ifpIdUniqueDocument')
            ->add('ifpIdTypeDocument')
            ->add('ifpIdLibelleDocument')
            ->add('ifpIdFormatDocument')
            ->add('ifpIdAuteurDocument')
            ->add('ifpIdSourceDocument')
            ->add('ifpIdNumVersionDocument')
            ->add('ifpIdPoidsDocument')
            ->add('ifpIdNbrPagesDocument')
            ->add('ifpIdProfilArchivage')
            ->add('ifpIdEtatArchive')
            ->add('ifpIdPeriodePaie')
            ->add('ifpIdPeriodeExerciceSociale')
            ->add('ifpIdDateDernierAccesDocument', DateTimeType::class)
            ->add('ifpIdDateArchivageDocument', DateTimeType::class)
            ->add('ifpIdDureeArchivageDocument')
            ->add('ifpIdDateFinArchivageDocument', DateTimeType::class)
            ->add('ifpIdNomSalarie')
            ->add('ifpIdPrenomSalarie')
            ->add('ifpIdNomJeuneFilleSalarie')
            ->add('ifpIdDateNaissanceSalarie', DateTimeType::class)
            ->add('ifpIdDateEntree', DateTimeType::class)
            ->add('ifpIdDateSortie', DateTimeType::class)
            ->add('ifpIdNumNir')
            ->add('ifpIdCodeCategProfessionnelle')
            ->add('ifpIdCodeCategSocioProf')
            ->add('ifpIdTypeContrat')
            ->add('ifpIdAffectation1')
            ->add('ifpIdAffectation2')
            ->add('ifpIdAffectation3')
            ->add('ifpIdLibre1')
            ->add('ifpIdLibre2')
            ->add('ifpIdAffectation1Date')
            ->add('ifpIdAffectation2Date')
            ->add('ifpIdAffectation3Date')
            ->add('ifpIdLibre1Date')
            ->add('ifpIdLibre2Date')
            ->add('ifpIdNumMatriculeRh')
            ->add('ifpIdNumMatriculeGroupe')
            ->add('ifpIdAnnotation')
            ->add('ifpIdConteneur')
            ->add('ifpIdBoite')
            ->add('ifpIdLot')
            ->add('ifpIdNumOrdre')
            ->add('ifpArchiveCfec')
            ->add('ifpArchiveSerialnumber')
            ->add('ifpArchiveDatetime')
            ->add('ifpArchiveName')
            ->add('ifpArchiveCfe')
            ->add('ifpNumeropdf')
            ->add('ifpOpnProvenance')
            ->add('ifpStatusNum')
            ->add('ifpIsDoublon')
            ->add('ifpLogin')
            ->add('ifpModedt')
            ->add('ifpNumdtr')
            ->add('ifpOldIdDateDernierAccesDocument')
            ->add('ifpOldIdDateArchivageDocument')
            ->add('ifpOldIdDateFinArchivageDocument')
            ->add('ifpOldIdDateNaissanceSalarie')
            ->add('ifpOldIdDateEntree')
            ->add('ifpOldIdDateSortie')
            ->add('ifpIdCodeActivite')
            ->add('ifpCycleFinDeVie')
            ->add('ifpCyclePurger')
            ->add('ifpGeler')
            ->add('ifpIdDate1')
            ->add('ifpIdDate2')
            ->add('ifpIdDate3')
            ->add('ifpIdDate4')
            ->add('ifpIdDate5')
            ->add('ifpIdDate6')
            ->add('ifpIdDate7')
            ->add('ifpIdDate8')
            ->add('ifpIdDateAdp1')
            ->add('ifpIdDateAdp2')
            ->add('ifpIdAlphanum1')
            ->add('ifpIdAlphanum2')
            ->add('ifpIdAlphanum3')
            ->add('ifpIdAlphanum4')
            ->add('ifpIdAlphanum5')
            ->add('ifpIdAlphanum6')
            ->add('ifpIdAlphanum7')
            ->add('ifpIdAlphanum8')
            ->add('ifpIdAlphanum9')
            ->add('ifpIdAlphanum10')
            ->add('ifpIdAlphanum11')
            ->add('ifpIdAlphanum12')
            ->add('ifpIdAlphanum13')
            ->add('ifpIdAlphanum14')
            ->add('ifpIdAlphanum15')
            ->add('ifpIdAlphanum16')
            ->add('ifpIdAlphanum17')
            ->add('ifpIdAlphanum18')
            ->add('ifpIdAlphanumAdp1')
            ->add('ifpIdAlphanumAdp2')
            ->add('ifpIdNum1')
            ->add('ifpIdNum2')
            ->add('ifpIdNum3')
            ->add('ifpIdNum4')
            ->add('ifpIdNum5')
            ->add('ifpIdNum6')
            ->add('ifpIdNum7')
            ->add('ifpIdNum8')
            ->add('ifpIdNum9')
            ->add('ifpIdNum10')
            ->add('ifpCycleTempsParcouru')
            ->add('ifpCycleTempsRestant')
            ->add('ifpSetFinArchivage')
            ->add('ifpIsPersonnel')
            ->add('ifpCreatedAt', null, array('mapped' => false))
            ->add('ifpUpdatedAt', null, array('mapped' => false))
            ->add('ifpNumMatricule')
            ->add('ifpCodeClient')
            ->add('ifpCodeDocument', null, array('invalid_message' => TypType::ERR_DOES_NOT_EXIST))
            ->add('ifpId', null, array('mapped' => false));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\IfpIndexfichePaperless',
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
