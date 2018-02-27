<?php

namespace ApiBundle\Tests\Entity;

use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\IinIdxIndiv;
use ApiBundle\Entity\TypType;

class IfpIndexfichePaperlessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IfpIndexfichePaperless
     */
    public $myObject;
    
    public $myWorker;
    public $myDocumentType;

    /**
     * @var \DateTime
     */
    public $now;

    /**
     * @var \DateTime
     */
    public $later;

    /**
     * @var \DateTime
     */
    public $date1;

    /**
     * @var \DateTime
     */
    public $date2;

    /**
     * @var \DateTime
     */
    public $date3;

    /**
     * @var \DateTime
     */
    public $date4;

    /**
     * @var \DateTime
     */
    public $date5;

    /**
     * @var \DateTime
     */
    public $date6;

    public function setUp()
    {
        $this->myObject = new IfpIndexfichePaperless();
        $this->myWorker = new IinIdxIndiv();
        $this->myDocumentType = new TypType();
        $this->now = new \DateTime();
        $this->later = new \DateTime();
        $this->later = $this->later->modify('+1H');
        $this->date1 = new \DateTime();
        $this->date1 = $this->date1->modify('+2H');
        $this->date2 = new \DateTime();
        $this->date2 = $this->date2->modify('-3D');
        $this->date3 = new \DateTime();
        $this->date3 = $this->date3->modify('+30Y');
        $this->date4 = new \DateTime();
        $this->date4 = $this->date4->modify('-30Y');
        $this->date5 = new \DateTime();
        $this->date5 = $this->date5->modify('-2Y');
        $this->date6 = new \DateTime();
        $this->date6= $this->date6->modify('-18M');

        // Test setter
        $this->setMyObject();

        parent::setUp();
    }

    public function testIfpIndexfichePaperlessEntity()
    {
        // Test Getter
        $this->testAsserts();
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function setMyObject()
    {
        $this->myObject->setIfpDocumentsassocies('MyIfpDocumentsassocies');
        $this->myObject->setIfpVdmLocalisation('MyIfpVdmLocalisation');
        $this->myObject->setIfpRefpapier('MyIfpRefpapier');
        $this->myObject->setIfpNombrepages(111);
        $this->myObject->setIfpIdCodeChrono('MyIfpIdCodeChrono');
        $this->myObject->setIfpIdNumeroBoiteArchive('MyIfpIdNumeroBoiteArchive');
        $this->myObject->setIfpInterbox(true);
        $this->myObject->setIfpLotIndex('MyIfpLotIndex');
        $this->myObject->setIfpLotProduction(222);
        $this->myObject->setIfpIdNomSociete('MyIfpIdNomSociete');
        $this->myObject->setIfpIdCompany('MyIfpIdCompany');
        $this->myObject->setIfpIdNomClient('MyIfpIdNomClient');
        $this->myObject->setIfpCodeClient('MyIfpIdCodeClient');
        $this->myObject->setIfpIdCodeSociete('MyIfpIdCodeSociete');
        $this->myObject->setIfpIdCodeEtablissement('MyIfpIdCodeEtablissement');
        $this->myObject->setIfpIdLibEtablissement('MyIfpIdLibelleEtablissement');
        $this->myObject->setIfpIdCodeJalon('MyIfpIdCodeJalon');
        $this->myObject->setIfpIdLibelleJalon('MyIfpIdLibelleJalon');
        $this->myObject->setIfpIdNumSiren('MyIfpIdNumSiren');
        $this->myObject->setIfpIdNumSiret('MyIfpIdNumSiret');
        $this->myObject->setIfpIdIndiceClassement('MyIfpIndiceClassement');
        $this->myObject->setIfpIdUniqueDocument('MyIfpIdUniqueDocument');
        $this->myObject->setIfpIdTypeDocument('MyIfpIdTypeDocument');
        $this->myObject->setIfpIdLibelleDocument('MyIfpIdLibelleDocument');
        $this->myObject->setIfpIdFormatDocument('MyIfpIdFormatDocument');
        $this->myObject->setIfpIdAuteurDocument('MyIfpIdAuteurDocument');
        $this->myObject->setIfpIdSourceDocument('MyIfpIdSourceDocument');
        $this->myObject->setIfpIdNumVersionDocument('MyIfpIdNumVersionDocument');
        $this->myObject->setIfpIdPoidsDocument('MyIfpIdPoidsDocument');
        $this->myObject->setIfpIdNbrPagesDocument('MyIfpIdNbrPagesDocument');
        $this->myObject->setIfpIdProfilArchivage('MyIfpIdProfilArchivage');
        $this->myObject->setIfpIdEtatArchive('MyIfpIdEtatArchive');
        $this->myObject->setIfpIdPeriodePaie('MyIfpIdPeriodePaie');
        $this->myObject->setIfpIdPeriodeExerciceSociale('MyIfpIdPeriodeExerciceSociale');
        $this->myObject->setIfpIdDateDernierAccesDocument($this->date1);
        $this->myObject->setIfpIdDateArchivageDocument($this->date2);
        $this->myObject->setIfpIdDureeArchivageDocument('MyIfpIdDureeArchivageDocument');
        $this->myObject->setIfpIdDateFinArchivageDocument($this->date3);
        $this->myObject->setIfpIdNomSalarie('MyIfpIdNomSalarie');
        $this->myObject->setIfpIdPrenomSalarie('MyIfpIdPrenomSalarie');
        $this->myObject->setIfpIdNomJeuneFilleSalarie('MyIfpIdNomJeuneFilleSalarie');
        $this->myObject->setIfpIdDateNaissanceSalarie($this->date4);
        $this->myObject->setIfpIdDateEntree($this->date5);
        $this->myObject->setIfpIdDateSortie($this->date6);
        $this->myObject->setIfpIdNumNir('MyIfpIdNumNir');
        $this->myObject->setIfpIdCodeCategProfessionnelle('MyIfpIdCodeCategProfessionnelle');
        $this->myObject->setIfpIdCodeCategSocioProf('MyIfpIdCodeCategSocioProf');
        $this->myObject->setIfpIdTypeContrat('MyIfpIdTypeContrat');
        $this->myObject->setIfpIdAffectation1('MyIfpIdAffectation1');
        $this->myObject->setIfpIdAffectation2('MyIfpIdAffectation2');
        $this->myObject->setIfpIdAffectation3('MyIfpIdAffectation3');
        $this->myObject->setIfpIdLibre1('MyIfpIdLibre1');
        $this->myObject->setIfpIdLibre2('MyIfpIdLibre2');
        $this->myObject->setIfpIdAffectation1Date('MyIfpIdAffectation1Date');
        $this->myObject->setIfpIdAffectation2Date('MyIfpIdAffectation2Date');
        $this->myObject->setIfpIdAffectation3Date('MyIfpIdAffectation3Date');
        $this->myObject->setIfpIdLibre1Date('MyIfpIdLibre1Date');
        $this->myObject->setIfpIdLibre2Date('MyIfpIdLibre2Date');
        $this->myObject->setIfpIdNumMatriculeRh('MyIfpIdMatriculeRh');
        $this->myObject->setIfpIdNumMatriculeGroupe('MyIfpIdMatriculeGroupe');
        $this->myObject->setIfpIdAnnotation('MyIfpIdAnnotation');
        $this->myObject->setIfpIdConteneur('MyIfpIdConteneur');
        $this->myObject->setIfpIdBoite('MyIfpIdBoite');
        $this->myObject->setIfpIdLot('MyIfpIdLot');
        $this->myObject->setIfpIdNumOrdre('MyIfpIdNumOrdre');
        $this->myObject->setIfpArchiveCfec('MyIfpArchiveCfec');
        $this->myObject->setIfpArchiveSerialnumber('MyIfpArchiveSerialnumber');
        $this->myObject->setIfpArchiveDatetime('MyIfpArchiveDateTime');
        $this->myObject->setIfpArchiveName('MyIfpArchiveName');
        $this->myObject->setIfpArchiveCfe('MyIfpArchiveCfe');
        $this->myObject->setIfpNumeropdf('MyIfpNumeropdf');
        $this->myObject->setIfpOpnProvenance('MyIfpOpnProvenance');
        $this->myObject->setIfpStatusNum('MyIfpStatusNum');
        $this->myObject->setIfpIsDoublon(false);
        $this->myObject->setIfpLogin('MyIfpLogin');
        $this->myObject->setIfpModedt('MyIfpModedt');
        $this->myObject->setIfpNumdtr('MyIfpNumdtr');
        $this->myObject->setIfpOldIdDateDernierAccesDocument('MyIfpOldIdDateDernierAccesDocument');
        $this->myObject->setIfpOldIdDateArchivageDocument('MyIfpOldIdDateArchivageDocument');
        $this->myObject->setIfpOldIdDateFinArchivageDocument('MyIfpOldIdDateFinArchivageDocument');
        $this->myObject->setIfpOldIdDateNaissanceSalarie('MyIfpOldIdDateNaissanceSalarie');
        $this->myObject->setIfpOldIdDateEntree('MyIfpOldIdDateEntree');
        $this->myObject->setIfpOldIdDateSortie('MyIfpOldIdDateSortie');
        $this->myObject->setIfpIdCodeActivite('MyIfpIdCodeActivite');
        $this->myObject->setIfpCycleFinDeVie('MyIfpCycleFinDeVie');
        $this->myObject->setIfpCyclePurger('MuIfpCyclePurger');
        $this->myObject->setIfpGeler('MyIfpGeler');
        $this->myObject->setIfpIdDate1('MyIfpIdDate1');
        $this->myObject->setIfpIdDate2('MyIfpIdDate2');
        $this->myObject->setIfpIdDate3('MyIfpIdDate3');
        $this->myObject->setIfpIdDate4('MyIfpIdDate4');
        $this->myObject->setIfpIdDate5('MyIfpIdDate5');
        $this->myObject->setIfpIdDate6('MyIfpIdDate6');
        $this->myObject->setIfpIdDate7('MyIfpIdDate7');
        $this->myObject->setIfpIdDate8('MyIfpIdDate8');
        $this->myObject->setIfpIdDateAdp1('MyIfpIdDateAdp1');
        $this->myObject->setIfpIdDateAdp2('MyIfpIdDateAdp2');
        $this->myObject->setIfpIdAlphanum1('MyIfpIdAlphanum1');
        $this->myObject->setIfpIdAlphanum2('MyIfpIdAlphanum2');
        $this->myObject->setIfpIdAlphanum3('MyIfpIdAlphanum3');
        $this->myObject->setIfpIdAlphanum4('MyIfpIdAlphanum4');
        $this->myObject->setIfpIdAlphanum5('MyIfpIdAlphanum5');
        $this->myObject->setIfpIdAlphanum6('MyIfpIdAlphanum6');
        $this->myObject->setIfpIdAlphanum7('MyIfpIdAlphanum7');
        $this->myObject->setIfpIdAlphanum8('MyIfpIdAlphanum8');
        $this->myObject->setIfpIdAlphanum9('MyIfpIdAlphanum9');
        $this->myObject->setIfpIdAlphanum10('MyIfpIdAlphanum10');
        $this->myObject->setIfpIdAlphanum11('MyIfpIdAlphanum11');
        $this->myObject->setIfpIdAlphanum12('MyIfpIdAlphanum12');
        $this->myObject->setIfpIdAlphanum13('MyIfpIdAlphanum13');
        $this->myObject->setIfpIdAlphanum14('MyIfpIdAlphanum14');
        $this->myObject->setIfpIdAlphanum15('MyIfpIdAlphanum15');
        $this->myObject->setIfpIdAlphanum16('MyIfpIdAlphanum16');
        $this->myObject->setIfpIdAlphanum17('MyIfpIdAlphanum17');
        $this->myObject->setIfpIdAlphanum18('MyIfpIdAlphanum18');
        $this->myObject->setIfpIdAlphanumAdp1('MyIfpIdAlphanumAdp1');
        $this->myObject->setIfpIdAlphanumAdp2('MyIfpIdAlphanumAdp2');
        $this->myObject->setIfpIdNum1(1.1);
        $this->myObject->setIfpIdNum2(2.2);
        $this->myObject->setIfpIdNum3(3.3);
        $this->myObject->setIfpIdNum4(4.4);
        $this->myObject->setIfpIdNum5(5.5);
        $this->myObject->setIfpIdNum6(6.6);
        $this->myObject->setIfpIdNum7(7.7);
        $this->myObject->setIfpIdNum8(8.8);
        $this->myObject->setIfpIdNum9(9.9);
        $this->myObject->setIfpIdNum10(10.10);
        $this->myObject->setIfpCycleTempsParcouru('MyIfpCycleTempsParcouru');
        $this->myObject->setIfpCycleTempsRestant('MyIfpCycleTempsRestant');
        $this->myObject->setIfpSetFinArchivage('MyIfpSetFinArchivage');
        $this->myObject->setIfpIsPersonnel(true);
        $this->myObject->setIfpNumMatricule($this->myWorker);
        $this->myObject->setIfpCodeDocument($this->myDocumentType);
        $this->myObject->setIfpCreatedAt($this->now);
        $this->myObject->setIfpUpdatedAt($this->later);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testAsserts()
    {
        $this->assertEquals(null, $this->myObject->getIfpId());
        $this->assertEquals('MyIfpDocumentsassocies', $this->myObject->getIfpDocumentsassocies());
        $this->assertEquals('MyIfpVdmLocalisation', $this->myObject->getIfpVdmLocalisation());
        $this->assertEquals('MyIfpRefpapier', $this->myObject->getIfpRefpapier());
        $this->assertEquals(111, $this->myObject->getIfpNombrepages());
        $this->assertEquals('MyIfpIdCodeChrono', $this->myObject->getIfpIdCodeChrono());
        $this->assertEquals('MyIfpIdNumeroBoiteArchive', $this->myObject->getIfpIdNumeroBoiteArchive());
        $this->assertEquals(true, $this->myObject->isIfpInterbox());
        $this->assertEquals('MyIfpLotIndex', $this->myObject->getIfpLotIndex());
        $this->assertEquals(222, $this->myObject->getIfpLotProduction());
        $this->assertEquals('MyIfpIdNomSociete', $this->myObject->getIfpIdNomSociete());
        $this->assertEquals('MyIfpIdCompany', $this->myObject->getIfpIdCompany());
        $this->assertEquals('MyIfpIdNomClient', $this->myObject->getIfpIdNomClient());
        $this->assertEquals('MyIfpIdCodeClient', $this->myObject->getIfpCodeClient());
        $this->assertEquals('MyIfpIdCodeSociete', $this->myObject->getIfpIdCodeSociete());
        $this->assertEquals('MyIfpIdCodeEtablissement', $this->myObject->getIfpIdCodeEtablissement());
        $this->assertEquals('MyIfpIdLibelleEtablissement', $this->myObject->getIfpIdLibEtablissement());
        $this->assertEquals('MyIfpIdCodeJalon', $this->myObject->getIfpIdCodeJalon());
        $this->assertEquals('MyIfpIdLibelleJalon', $this->myObject->getIfpIdLibelleJalon());
        $this->assertEquals('MyIfpIdNumSiren', $this->myObject->getIfpIdNumSiren());
        $this->assertEquals('MyIfpIdNumSiret', $this->myObject->getIfpIdNumSiret());
        $this->assertEquals('MyIfpIndiceClassement', $this->myObject->getIfpIdIndiceClassement());
        $this->assertEquals('MyIfpIdUniqueDocument', $this->myObject->getIfpIdUniqueDocument());
        $this->assertEquals('MyIfpIdTypeDocument', $this->myObject->getIfpIdTypeDocument());
        $this->assertEquals('MyIfpIdLibelleDocument', $this->myObject->getIfpIdLibelleDocument());
        $this->assertEquals('MyIfpIdFormatDocument', $this->myObject->getIfpIdFormatDocument());
        $this->assertEquals('MyIfpIdAuteurDocument', $this->myObject->getIfpIdAuteurDocument());
        $this->assertEquals('MyIfpIdSourceDocument', $this->myObject->getIfpIdSourceDocument());
        $this->assertEquals('MyIfpIdNumVersionDocument', $this->myObject->getIfpIdNumVersionDocument());
        $this->assertEquals('MyIfpIdPoidsDocument', $this->myObject->getIfpIdPoidsDocument());
        $this->assertEquals('MyIfpIdNbrPagesDocument', $this->myObject->getIfpIdNbrPagesDocument());
        $this->assertEquals('MyIfpIdProfilArchivage', $this->myObject->getIfpIdProfilArchivage());
        $this->assertEquals('MyIfpIdEtatArchive', $this->myObject->getIfpIdEtatArchive());
        $this->assertEquals('MyIfpIdPeriodePaie', $this->myObject->getIfpIdPeriodePaie());
        $this->assertEquals('MyIfpIdPeriodeExerciceSociale', $this->myObject->getIfpIdPeriodeExerciceSociale());
        $this->assertEquals(
            $this->date1->getTimestamp(),
            $this->myObject->getIfpIdDateDernierAccesDocument()->getTimestamp()
        );
        $this->assertEquals(
            $this->date2->getTimestamp(),
            $this->myObject->getIfpIdDateArchivageDocument()->getTimestamp()
        );
        $this->assertEquals('MyIfpIdDureeArchivageDocument', $this->myObject->getIfpIdDureeArchivageDocument());
        $this->assertEquals(
            $this->date3->getTimestamp(),
            $this->myObject->getIfpIdDateFinArchivageDocument()->getTimestamp()
        );
        $this->assertEquals('MyIfpIdNomSalarie', $this->myObject->getIfpIdNomSalarie());
        $this->assertEquals('MyIfpIdPrenomSalarie', $this->myObject->getIfpIdPrenomSalarie());
        $this->assertEquals('MyIfpIdNomJeuneFilleSalarie', $this->myObject->getIfpIdNomJeuneFilleSalarie());
        $this->assertEquals(
            $this->date4->getTimestamp(),
            $this->myObject->getIfpIdDateNaissanceSalarie()->getTimestamp()
        );
        $this->assertEquals(
            $this->date5->getTimestamp(),
            $this->myObject->getIfpIdDateEntree()->getTimestamp()
        );
        $this->assertEquals(
            $this->date6->getTimestamp(),
            $this->myObject->getIfpIdDateSortie()->getTimestamp()
        );
        $this->assertEquals('MyIfpIdNumNir', $this->myObject->getIfpIdNumNir());
        $this->assertEquals('MyIfpIdCodeCategProfessionnelle', $this->myObject->getIfpIdCodeCategProfessionnelle());
        $this->assertEquals('MyIfpIdCodeCategSocioProf', $this->myObject->getIfpIdCodeCategSocioProf());
        $this->assertEquals('MyIfpIdTypeContrat', $this->myObject->getIfpIdTypeContrat());
        $this->assertEquals('MyIfpIdAffectation1', $this->myObject->getIfpIdAffectation1());
        $this->assertEquals('MyIfpIdAffectation2', $this->myObject->getIfpIdAffectation2());
        $this->assertEquals('MyIfpIdAffectation3', $this->myObject->getIfpIdAffectation3());
        $this->assertEquals('MyIfpIdLibre1', $this->myObject->getIfpIdLibre1());
        $this->assertEquals('MyIfpIdLibre2', $this->myObject->getIfpIdLibre2());
        $this->assertEquals('MyIfpIdAffectation1Date', $this->myObject->getIfpIdAffectation1Date());
        $this->assertEquals('MyIfpIdAffectation2Date', $this->myObject->getIfpIdAffectation2Date());
        $this->assertEquals('MyIfpIdAffectation3Date', $this->myObject->getIfpIdAffectation3Date());
        $this->assertEquals('MyIfpIdLibre1Date', $this->myObject->getIfpIdLibre1Date());
        $this->assertEquals('MyIfpIdLibre2Date', $this->myObject->getIfpIdLibre2Date());
        $this->assertEquals('MyIfpIdMatriculeRh', $this->myObject->getIfpIdNumMatriculeRh());
        $this->assertEquals('MyIfpIdMatriculeGroupe', $this->myObject->getIfpIdNumMatriculeGroupe());
        $this->assertEquals('MyIfpIdAnnotation', $this->myObject->getIfpIdAnnotation());
        $this->assertEquals('MyIfpIdConteneur', $this->myObject->getIfpIdConteneur());
        $this->assertEquals('MyIfpIdBoite', $this->myObject->getIfpIdBoite());
        $this->assertEquals('MyIfpIdLot', $this->myObject->getIfpIdLot());
        $this->assertEquals('MyIfpIdNumOrdre', $this->myObject->getIfpIdNumOrdre());
        $this->assertEquals('MyIfpArchiveCfec', $this->myObject->getIfpArchiveCfec());
        $this->assertEquals('MyIfpArchiveSerialnumber', $this->myObject->getIfpArchiveSerialnumber());
        $this->assertEquals('MyIfpArchiveDateTime', $this->myObject->getIfpArchiveDatetime());
        $this->assertEquals('MyIfpArchiveName', $this->myObject->getIfpArchiveName());
        $this->assertEquals('MyIfpArchiveCfe', $this->myObject->getIfpArchiveCfe());
        $this->assertEquals('MyIfpNumeropdf', $this->myObject->getIfpNumeropdf());
        $this->assertEquals('MyIfpOpnProvenance', $this->myObject->getIfpOpnProvenance());
        $this->assertEquals('MyIfpStatusNum', $this->myObject->getIfpStatusNum());
        $this->assertEquals(false, $this->myObject->isIfpIsDoublon());
        $this->assertEquals('MyIfpLogin', $this->myObject->getIfpLogin());
        $this->assertEquals('MyIfpModedt', $this->myObject->getIfpModedt());
        $this->assertEquals('MyIfpNumdtr', $this->myObject->getIfpNumdtr());
        $this->assertEquals(
            'MyIfpOldIdDateDernierAccesDocument',
            $this->myObject->getIfpOldIdDateDernierAccesDocument()
        );
        $this->assertEquals('MyIfpOldIdDateArchivageDocument', $this->myObject->getIfpOldIdDateArchivageDocument());
        $this->assertEquals(
            'MyIfpOldIdDateFinArchivageDocument',
            $this->myObject->getIfpOldIdDateFinArchivageDocument()
        );
        $this->assertEquals('MyIfpOldIdDateNaissanceSalarie', $this->myObject->getIfpOldIdDateNaissanceSalarie());
        $this->assertEquals('MyIfpOldIdDateEntree', $this->myObject->getIfpOldIdDateEntree());
        $this->assertEquals('MyIfpOldIdDateSortie', $this->myObject->getIfpOldIdDateSortie());
        $this->assertEquals('MyIfpIdCodeActivite', $this->myObject->getIfpIdCodeActivite());
        $this->assertEquals('MyIfpCycleFinDeVie', $this->myObject->isIfpCycleFinDeVie());
        $this->assertEquals('MuIfpCyclePurger', $this->myObject->getIfpCyclePurger());
        $this->assertEquals('MyIfpGeler', $this->myObject->isIfpGeler());
        $this->assertEquals('MyIfpIdDate1', $this->myObject->getIfpIdDate1());
        $this->assertEquals('MyIfpIdDate2', $this->myObject->getIfpIdDate2());
        $this->assertEquals('MyIfpIdDate3', $this->myObject->getIfpIdDate3());
        $this->assertEquals('MyIfpIdDate4', $this->myObject->getIfpIdDate4());
        $this->assertEquals('MyIfpIdDate5', $this->myObject->getIfpIdDate5());
        $this->assertEquals('MyIfpIdDate6', $this->myObject->getIfpIdDate6());
        $this->assertEquals('MyIfpIdDate7', $this->myObject->getIfpIdDate7());
        $this->assertEquals('MyIfpIdDate8', $this->myObject->getIfpIdDate8());
        $this->assertEquals('MyIfpIdDateAdp1', $this->myObject->getIfpIdDateAdp1());
        $this->assertEquals('MyIfpIdDateAdp2', $this->myObject->getIfpIdDateAdp2());
        $this->assertEquals('MyIfpIdAlphanum1', $this->myObject->getIfpIdAlphanum1());
        $this->assertEquals('MyIfpIdAlphanum2', $this->myObject->getIfpIdAlphanum2());
        $this->assertEquals('MyIfpIdAlphanum3', $this->myObject->getIfpIdAlphanum3());
        $this->assertEquals('MyIfpIdAlphanum4', $this->myObject->getIfpIdAlphanum4());
        $this->assertEquals('MyIfpIdAlphanum5', $this->myObject->getIfpIdAlphanum5());
        $this->assertEquals('MyIfpIdAlphanum6', $this->myObject->getIfpIdAlphanum6());
        $this->assertEquals('MyIfpIdAlphanum7', $this->myObject->getIfpIdAlphanum7());
        $this->assertEquals('MyIfpIdAlphanum8', $this->myObject->getIfpIdAlphanum8());
        $this->assertEquals('MyIfpIdAlphanum9', $this->myObject->getIfpIdAlphanum9());
        $this->assertEquals('MyIfpIdAlphanum10', $this->myObject->getIfpIdAlphanum10());
        $this->assertEquals('MyIfpIdAlphanum11', $this->myObject->getIfpIdAlphanum11());
        $this->assertEquals('MyIfpIdAlphanum12', $this->myObject->getIfpIdAlphanum12());
        $this->assertEquals('MyIfpIdAlphanum13', $this->myObject->getIfpIdAlphanum13());
        $this->assertEquals('MyIfpIdAlphanum14', $this->myObject->getIfpIdAlphanum14());
        $this->assertEquals('MyIfpIdAlphanum15', $this->myObject->getIfpIdAlphanum15());
        $this->assertEquals('MyIfpIdAlphanum16', $this->myObject->getIfpIdAlphanum16());
        $this->assertEquals('MyIfpIdAlphanum17', $this->myObject->getIfpIdAlphanum17());
        $this->assertEquals('MyIfpIdAlphanum18', $this->myObject->getIfpIdAlphanum18());
        $this->assertEquals('MyIfpIdAlphanumAdp1', $this->myObject->getIfpIdAlphanumAdp1());
        $this->assertEquals('MyIfpIdAlphanumAdp2', $this->myObject->getIfpIdAlphanumAdp2());
        $this->assertEquals(1.1, $this->myObject->getIfpIdNum1());
        $this->assertEquals(2.2, $this->myObject->getIfpIdNum2());
        $this->assertEquals(3.3, $this->myObject->getIfpIdNum3());
        $this->assertEquals(4.4, $this->myObject->getIfpIdNum4());
        $this->assertEquals(5.5, $this->myObject->getIfpIdNum5());
        $this->assertEquals(6.6, $this->myObject->getIfpIdNum6());
        $this->assertEquals(7.7, $this->myObject->getIfpIdNum7());
        $this->assertEquals(8.8, $this->myObject->getIfpIdNum8());
        $this->assertEquals(9.9, $this->myObject->getIfpIdNum9());
        $this->assertEquals(10.10, $this->myObject->getIfpIdNum10());
        $this->assertEquals('MyIfpCycleTempsParcouru', $this->myObject->getIfpCycleTempsParcouru());
        $this->assertEquals('MyIfpCycleTempsRestant', $this->myObject->getIfpCycleTempsRestant());
        $this->assertEquals('MyIfpSetFinArchivage', $this->myObject->isIfpSetFinArchivage());
        $this->assertEquals(true, $this->myObject->isIfpIsPersonnel());
        $this->assertInstanceOf('ApiBundle\Entity\IinIdxIndiv', $this->myObject->getIfpNumMatricule());
        $this->assertInstanceOf('ApiBundle\Entity\TypType', $this->myObject->getIfpCodeDocument());
        $this->assertEquals($this->now->getTimestamp(), $this->myObject->getIfpCreatedAt()->getTimestamp());
        $this->assertEquals($this->later->getTimestamp(), $this->myObject->getIfpUpdatedAt()->getTimestamp());
    }
}
