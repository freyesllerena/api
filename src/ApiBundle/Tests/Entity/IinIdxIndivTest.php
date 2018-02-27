<?php

namespace ApiBundle\Tests\Entity;

use ApiBundle\Entity\IinIdxIndiv;

class IinIdxIndivTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testIinIdxIndivEntity()
    {
        $myObject = new IinIdxIndiv();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');
        $date1 = new \DateTime();
        $date1 = $date1->modify('-2Y');
        $date2 = new \DateTime();
        $date2= $date2->modify('-18M');
        $date3 = new \DateTime();
        $date3= $date3->modify('-35Y');

        // Test setter
        $myObject->setIinCodeClient('MyIinCodeClient');
        $myObject->setIinIdCodeSociete('MyIinIdCodeSociete');
        $myObject->setIinIdCodeJalon('MyIinIdCodeJalon');
        $myObject->setIinIdCodeEtablissement('MyIinIdCodeEtablissement');
        $myObject->setIinIdLibEtablissement('MyIinIdLibEtablissement');
        $myObject->setIinIdNomSociete('MyIinIdNomSociete');
        $myObject->setIinIdNomClient('MyIinIdNomClient');
        $myObject->setIinIdTypePaie('MyIinTypePaie');
        $myObject->setIinIdPeriodePaie('MyIinIdPeriodePaie');
        $myObject->setIinIdNomSalarie('MyIinIdNomSalarie');
        $myObject->setIinIdPrenomSalarie('MyIinIdPrenomSalarie');
        $myObject->setIinIdNomJeuneFilleSalarie('MyIinIdNomJeuneFilleSalarie');
        $myObject->setIinIdDateEntree($date1);
        $myObject->setIinIdDateSortie($date2);
        $myObject->setIinIdNumNir('MyIinIdNumNir');
        $myObject->setIinNumMatricule('MyIinNumMatricule');
        $myObject->setIinFichierIndex('MyIinFichierIndex');
        $myObject->setIinIdCodeCategProfessionnelle('MyIinIdCodeCategProfessionnelle');
        $myObject->setIinIdCodeCategSocioProf('MyIinCodeCategSocioProf');
        $myObject->setIinIdTypeContrat('MyIinIdTypeContrat');
        $myObject->setIinIdAffectation1('MyIinIdAffectation1');
        $myObject->setIinIdAffectation2('MyIinIdAffectation2');
        $myObject->setIinIdAffectation3('MyIinIdAffectation3');
        $myObject->setIinIdNumSiren('MyIinIdNumSiren');
        $myObject->setIinIdNumSiret('MyIinIdNumSiret');
        $myObject->setIinIdDateNaissanceSalarie($date3);
        $myObject->setIinIdLibre1('MyIinIdLibre1');
        $myObject->setIinIdLibre2('MyIinIdLibre2');
        $myObject->setIinIdNumMatriculeGroupe('MyIinIdNumMatriculeGroupe');
        $myObject->setIinIdNumMatriculeRh('MyIinIdNumMatriculeRh');
        $myObject->setIinIdCodeActivite('MyIinIdCodeActivite');
        $myObject->setIinIdCodeChrono('MyIinIdCodeChrono');
        $myObject->setIinIdDate1('MyIinIdDate1');
        $myObject->setIinIdDate2('MyIinIdDate2');
        $myObject->setIinIdDate3('MyIinIdDate3');
        $myObject->setIinIdDate4('MyIinIdDate4');
        $myObject->setIinIdDate5('MyIinIdDate5');
        $myObject->setIinIdDate6('MyIinIdDate6');
        $myObject->setIinIdDate7('MyIinIdDate7');
        $myObject->setIinIdDate8('MyIinIdDate8');
        $myObject->setIinIdDateAdp1('MyIinIdDateAdp1');
        $myObject->setIinIdDateAdp2('MyIinIdDateAdp2');
        $myObject->setIinIdAlphanum1('MyIinIdAlphanum1');
        $myObject->setIinIdAlphanum2('MyIinIdAlphanum2');
        $myObject->setIinIdAlphanum3('MyIinIdAlphanum3');
        $myObject->setIinIdAlphanum4('MyIinIdAlphanum4');
        $myObject->setIinIdAlphanum5('MyIinIdAlphanum5');
        $myObject->setIinIdAlphanum6('MyIinIdAlphanum6');
        $myObject->setIinIdAlphanum7('MyIinIdAlphanum7');
        $myObject->setIinIdAlphanum8('MyIinIdAlphanum8');
        $myObject->setIinIdAlphanum9('MyIinIdAlphanum9');
        $myObject->setIinIdAlphanum10('MyIinIdAlphanum10');
        $myObject->setIinIdAlphanum11('MyIinIdAlphanum11');
        $myObject->setIinIdAlphanum12('MyIinIdAlphanum12');
        $myObject->setIinIdAlphanum13('MyIinIdAlphanum13');
        $myObject->setIinIdAlphanum14('MyIinIdAlphanum14');
        $myObject->setIinIdAlphanum15('MyIinIdAlphanum15');
        $myObject->setIinIdAlphanum16('MyIinIdAlphanum16');
        $myObject->setIinIdAlphanum17('MyIinIdAlphanum17');
        $myObject->setIinIdAlphanum18('MyIinIdAlphanum18');
        $myObject->setIinIdAlphanumAdp1('MyIinIdAlphanumAdp1');
        $myObject->setIinIdAlphanumAdp2('MyIinIdAlphanumAdp2');
        $myObject->setIinIdNum1('MyIinIdNum1');
        $myObject->setIinIdNum2('MyIinIdNum2');
        $myObject->setIinIdNum3('MyIinIdNum3');
        $myObject->setIinIdNum4('MyIinIdNum4');
        $myObject->setIinIdNum5('MyIinIdNum5');
        $myObject->setIinIdNum6('MyIinIdNum6');
        $myObject->setIinIdNum7('MyIinIdNum7');
        $myObject->setIinIdNum8('MyIinIdNum8');
        $myObject->setIinIdNum9('MyIinIdNum9');
        $myObject->setIinIdNum10('MyIinIdNum10');
        $myObject->setIinIdNumOrdre('MyIinIdNumOrdre');
        $myObject->setIinCreatedAt($now);
        $myObject->setIinUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIinId());

        $this->assertEquals('MyIinCodeClient', $myObject->getIinCodeClient());
        $this->assertEquals('MyIinIdCodeSociete', $myObject->getIinIdCodeSociete());
        $this->assertEquals('MyIinIdCodeJalon', $myObject->getIinIdCodeJalon());
        $this->assertEquals('MyIinIdCodeEtablissement', $myObject->getIinIdCodeEtablissement());
        $this->assertEquals('MyIinIdLibEtablissement', $myObject->getIinIdLibEtablissement());
        $this->assertEquals('MyIinIdNomSociete', $myObject->getIinIdNomSociete());
        $this->assertEquals('MyIinIdNomClient', $myObject->getIinIdNomClient());
        $this->assertEquals('MyIinTypePaie', $myObject->getIinIdTypePaie());
        $this->assertEquals('MyIinIdPeriodePaie', $myObject->getIinIdPeriodePaie());
        $this->assertEquals('MyIinIdNomSalarie', $myObject->getIinIdNomSalarie());
        $this->assertEquals('MyIinIdPrenomSalarie', $myObject->getIinIdPrenomSalarie());
        $this->assertEquals('MyIinIdNomJeuneFilleSalarie', $myObject->getIinIdNomJeuneFilleSalarie());
        $this->assertEquals($date1->getTimestamp(), $myObject->getIinIdDateEntree()->getTimestamp());
        $this->assertEquals($date2->getTimestamp(), $myObject->getIinIdDateSortie()->getTimestamp());
        $this->assertEquals('MyIinIdNumNir', $myObject->getIinIdNumNir());
        $this->assertEquals('MyIinNumMatricule', $myObject->getIinNumMatricule());
        $this->assertEquals('MyIinFichierIndex', $myObject->getIinFichierIndex());
        $this->assertEquals('MyIinIdCodeCategProfessionnelle', $myObject->getIinIdCodeCategProfessionnelle());
        $this->assertEquals('MyIinCodeCategSocioProf', $myObject->getIinIdCodeCategSocioProf());
        $this->assertEquals('MyIinIdTypeContrat', $myObject->getIinIdTypeContrat());
        $this->assertEquals('MyIinIdAffectation1', $myObject->getIinIdAffectation1());
        $this->assertEquals('MyIinIdAffectation2', $myObject->getIinIdAffectation2());
        $this->assertEquals('MyIinIdAffectation3', $myObject->getIinIdAffectation3());
        $this->assertEquals('MyIinIdNumSiren', $myObject->getIinIdNumSiren());
        $this->assertEquals('MyIinIdNumSiret', $myObject->getIinIdNumSiret());
        $this->assertEquals($date3->getTimestamp(), $myObject->getIinIdDateNaissanceSalarie()->getTimestamp());
        $this->assertEquals('MyIinIdLibre1', $myObject->getIinIdLibre1());
        $this->assertEquals('MyIinIdLibre2', $myObject->getIinIdLibre2());
        $this->assertEquals('MyIinIdNumMatriculeGroupe', $myObject->getIinIdNumMatriculeGroupe());
        $this->assertEquals('MyIinIdNumMatriculeRh', $myObject->getIinIdNumMatriculeRh());
        $this->assertEquals('MyIinIdCodeActivite', $myObject->getIinIdCodeActivite());
        $this->assertEquals('MyIinIdCodeChrono', $myObject->getIinIdCodeChrono());
        $this->assertEquals('MyIinIdDate1', $myObject->getIinIdDate1());
        $this->assertEquals('MyIinIdDate2', $myObject->getIinIdDate2());
        $this->assertEquals('MyIinIdDate3', $myObject->getIinIdDate3());
        $this->assertEquals('MyIinIdDate4', $myObject->getIinIdDate4());
        $this->assertEquals('MyIinIdDate5', $myObject->getIinIdDate5());
        $this->assertEquals('MyIinIdDate6', $myObject->getIinIdDate6());
        $this->assertEquals('MyIinIdDate7', $myObject->getIinIdDate7());
        $this->assertEquals('MyIinIdDate8', $myObject->getIinIdDate8());
        $this->assertEquals('MyIinIdDateAdp1', $myObject->getIinIdDateAdp1());
        $this->assertEquals('MyIinIdDateAdp2', $myObject->getIinIdDateAdp2());
        $this->assertEquals('MyIinIdAlphanum1', $myObject->getIinIdAlphanum1());
        $this->assertEquals('MyIinIdAlphanum2', $myObject->getIinIdAlphanum2());
        $this->assertEquals('MyIinIdAlphanum3', $myObject->getIinIdAlphanum3());
        $this->assertEquals('MyIinIdAlphanum4', $myObject->getIinIdAlphanum4());
        $this->assertEquals('MyIinIdAlphanum5', $myObject->getIinIdAlphanum5());
        $this->assertEquals('MyIinIdAlphanum6', $myObject->getIinIdAlphanum6());
        $this->assertEquals('MyIinIdAlphanum7', $myObject->getIinIdAlphanum7());
        $this->assertEquals('MyIinIdAlphanum8', $myObject->getIinIdAlphanum8());
        $this->assertEquals('MyIinIdAlphanum9', $myObject->getIinIdAlphanum9());
        $this->assertEquals('MyIinIdAlphanum10', $myObject->getIinIdAlphanum10());
        $this->assertEquals('MyIinIdAlphanum11', $myObject->getIinIdAlphanum11());
        $this->assertEquals('MyIinIdAlphanum12', $myObject->getIinIdAlphanum12());
        $this->assertEquals('MyIinIdAlphanum13', $myObject->getIinIdAlphanum13());
        $this->assertEquals('MyIinIdAlphanum14', $myObject->getIinIdAlphanum14());
        $this->assertEquals('MyIinIdAlphanum15', $myObject->getIinIdAlphanum15());
        $this->assertEquals('MyIinIdAlphanum16', $myObject->getIinIdAlphanum16());
        $this->assertEquals('MyIinIdAlphanum17', $myObject->getIinIdAlphanum17());
        $this->assertEquals('MyIinIdAlphanum18', $myObject->getIinIdAlphanum18());
        $this->assertEquals('MyIinIdAlphanumAdp1', $myObject->getIinIdAlphanumAdp1());
        $this->assertEquals('MyIinIdAlphanumAdp2', $myObject->getIinIdAlphanumAdp2());
        $this->assertEquals('MyIinIdNum1', $myObject->getIinIdNum1());
        $this->assertEquals('MyIinIdNum2', $myObject->getIinIdNum2());
        $this->assertEquals('MyIinIdNum3', $myObject->getIinIdNum3());
        $this->assertEquals('MyIinIdNum4', $myObject->getIinIdNum4());
        $this->assertEquals('MyIinIdNum5', $myObject->getIinIdNum5());
        $this->assertEquals('MyIinIdNum6', $myObject->getIinIdNum6());
        $this->assertEquals('MyIinIdNum7', $myObject->getIinIdNum7());
        $this->assertEquals('MyIinIdNum8', $myObject->getIinIdNum8());
        $this->assertEquals('MyIinIdNum9', $myObject->getIinIdNum9());
        $this->assertEquals('MyIinIdNum10', $myObject->getIinIdNum10());
        $this->assertEquals('MyIinIdNumOrdre', $myObject->getIinIdNumOrdre());
        $this->assertEquals($now->getTimestamp(), $myObject->getIinCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIinUpdatedAt()->getTimestamp());
    }
}
