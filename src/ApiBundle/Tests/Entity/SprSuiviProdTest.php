<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\SprSuiviProd;

class SprSuiviProdTest extends \PHPUnit_Framework_TestCase
{
    public function testRimRapportImportEntity()
    {
        $myObject = new SprSuiviProd();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setSprFichierDat('MySprFichierDat');
        $myObject->setSprNumeroBdl('MySprNumeroBdl');
        $myObject->setSprChronoOdin(123456789);
        $myObject->setSprBanniereDebut('MySprBanniereDebut');
        $myObject->setSprBanniereFin('MySprBanniereFin');
        $myObject->setSprCodeClient('MySprCodeClient');
        $myObject->setSprCodeApplication('MySprCodeApplication');
        $myObject->setSprTypeOperation('MySprTypeOperation');
        $myObject->setSprChronoClient(10);
        $myObject->setSprSpool('MySprSpool');
        $myObject->setSprFichierLogEmpreinte('MySprFichierLogEmpreinte');
        $myObject->setSprMaxEnregAvant(1000);
        $myObject->setSprNombreEnregsAvant(900);
        $myObject->setSprMaxEnregApres(2000);
        $myObject->setSprNombreEnregsApres(1800);
        $myObject->setSprNombrePagesHebergeesAvant(888);
        $myObject->setSprNombrePagesHebergeesApres(777);
        $myObject->setSprNombreDossiers(36);
        $myObject->setSprNombrePages(28);
        $myObject->setSprNombrePagesVides(12);
        $myObject->setSprPremierIndex('MySprPremierIndex');
        $myObject->setSprDernierIndex('MySprDernierIndex');
        $myObject->setSprTempsPdf(1);
        $myObject->setSprTempsIndexation(2);
        $myObject->setSprTempsInjection(3);
        $myObject->setSprTempsAutre(4);
        $myObject->setSprCompteurDossiersTheorique(5);
        $myObject->setSprCompteurPagesTheorique(20);
        $myObject->setSprTableIndexfiche('MySprTableIndexfiche');
        $myObject->setSprAudit('MySprAudit');
        $myObject->setSprLotIndex('MySprLotIndex');
        $myObject->setSprLotProduction(120);
        $myObject->setSprCodeClientAppelant('MySprCodeClientAppelant');
        $myObject->setSprCodeApplicationAppelant('MySprCodeApplicationAppelant');
        $myObject->setSprNumerobdlAppelant('MySprNumerobdlAppelant');
        $myObject->setSprInformationClient('MySprInformationClient');
        $myObject->setSprCreatedAt($now);
        $myObject->setSprUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getSprId());
        $this->assertEquals('MySprFichierDat', $myObject->getSprFichierDat());
        $this->assertEquals('MySprNumeroBdl', $myObject->getSprNumeroBdl());
        $this->assertEquals(123456789, $myObject->getSprChronoOdin());
        $this->assertEquals('MySprBanniereDebut', $myObject->getSprBanniereDebut());
        $this->assertEquals('MySprBanniereFin', $myObject->getSprBanniereFin());
        $this->assertEquals('MySprCodeClient', $myObject->getSprCodeClient());
        $this->assertEquals('MySprCodeApplication', $myObject->getSprCodeApplication());
        $this->assertEquals('MySprTypeOperation', $myObject->getSprTypeOperation());
        $this->assertEquals(10, $myObject->getSprChronoClient());
        $this->assertEquals('MySprSpool', $myObject->getSprSpool());
        $this->assertEquals('MySprFichierLogEmpreinte', $myObject->getSprFichierLogEmpreinte());
        $this->assertEquals(1000, $myObject->getSprMaxEnregAvant());
        $this->assertEquals(900, $myObject->getSprNombreEnregsAvant());
        $this->assertEquals(2000, $myObject->getSprMaxEnregApres());
        $this->assertEquals(1800, $myObject->getSprNombreEnregsApres());
        $this->assertEquals(888, $myObject->getSprNombrePagesHebergeesAvant());
        $this->assertEquals(777, $myObject->getSprNombrePagesHebergeesApres());
        $this->assertEquals(36, $myObject->getSprNombreDossiers());
        $this->assertEquals(28, $myObject->getSprNombrePages());
        $this->assertEquals(12, $myObject->getSprNombrePagesVides());
        $this->assertEquals('MySprPremierIndex', $myObject->getSprPremierIndex());
        $this->assertEquals('MySprDernierIndex', $myObject->getSprDernierIndex());
        $this->assertEquals(1, $myObject->getSprTempsPdf());
        $this->assertEquals(2, $myObject->getSprTempsIndexation());
        $this->assertEquals(3, $myObject->getSprTempsInjection());
        $this->assertEquals(4, $myObject->getSprTempsAutre());
        $this->assertEquals(5, $myObject->getSprCompteurDossiersTheorique());
        $this->assertEquals(20, $myObject->getSprCompteurPagesTheorique());
        $this->assertEquals('MySprTableIndexfiche', $myObject->getSprTableIndexfiche());
        $this->assertEquals('MySprAudit', $myObject->getSprAudit());
        $this->assertEquals('MySprLotIndex', $myObject->getSprLotIndex());
        $this->assertEquals(120, $myObject->getSprLotProduction());
        $this->assertEquals('MySprCodeClientAppelant', $myObject->getSprCodeClientAppelant());
        $this->assertEquals('MySprCodeApplicationAppelant', $myObject->getSprCodeApplicationAppelant());
        $this->assertEquals('MySprNumerobdlAppelant', $myObject->getSprNumerobdlAppelant());
        $this->assertEquals('MySprInformationClient', $myObject->getSprInformationClient());
        $this->assertEquals($now->getTimestamp(), $myObject->getSprCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getSprUpdatedAt()->getTimestamp());
    }
}