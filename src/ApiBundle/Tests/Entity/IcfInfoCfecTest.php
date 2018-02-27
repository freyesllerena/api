<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\IcfInfoCfec;

class IcfInfoCfecTest extends \PHPUnit_Framework_TestCase
{
    public function testIcfInfoCfecEntity()
    {
        $myObject = new IcfInfoCfec();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setIcfIdAlias('MyIcfIdAlias');
        $myObject->setIcfCfecBasePrincipal('MyIcfCfecBasePrincipal');
        $myObject->setIcfCfecCertPrincipal('MyIcfCfecCertPrincipal');
        $myObject->setIcfCfecNumeroCfePrincipal('MyIcfCfecNumeroCfePrincipal');
        $myObject->setIcfCfecNumeroCfecPrincipal('MyIcfCfecNumeroCfecPrincipal');
        $myObject->setIcfCfecBaseSecondaire('MyIcfCfecBaseSecondaire');
        $myObject->setIcfCfecCertSecondaire('MyIcfCfecCertSecondaire');
        $myObject->setIcfCfecNumeroCfeSecondaire('MyIcfCfecNumeroCfeSecondaire');
        $myObject->setIcfCfecNumeroCfecSecondaire('MyIcfCfecNumeroCfecSecondaire');
        $myObject->setIcfCiUrlPrincipal('MyIcfiUrlPrincipal');
        $myObject->setIcfCiRepTransferPrincipal('MyIcfiRepTransferPrincipal');
        $myObject->setIcfCiRepCrPrincipal('MyIcfiRepCrPrincipal');
        $myObject->setIcfCiUrlSecondaire('MyIcfCiUrlSecondaire');
        $myObject->setIcfCiRepTransferSecondaire('MyIcfiRepTransferSecondaire');
        $myObject->setIcfCiRepCrSecondaire('MyIcfCiRepCrSecondaire');
        $myObject->setIcfCreatedAt($now);
        $myObject->setIcfUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIcfId());
        $this->assertEquals('MyIcfIdAlias', $myObject->getIcfIdAlias());
        $this->assertEquals('MyIcfCfecBasePrincipal', $myObject->getIcfCfecBasePrincipal());
        $this->assertEquals('MyIcfCfecCertPrincipal', $myObject->getIcfCfecCertPrincipal());
        $this->assertEquals('MyIcfCfecNumeroCfePrincipal', $myObject->getIcfCfecNumeroCfePrincipal());
        $this->assertEquals('MyIcfCfecNumeroCfecPrincipal', $myObject->getIcfCfecNumeroCfecPrincipal());
        $this->assertEquals('MyIcfCfecBaseSecondaire', $myObject->getIcfCfecBaseSecondaire());
        $this->assertEquals('MyIcfCfecCertSecondaire', $myObject->getIcfCfecCertSecondaire());
        $this->assertEquals('MyIcfCfecNumeroCfeSecondaire', $myObject->getIcfCfecNumeroCfeSecondaire());
        $this->assertEquals('MyIcfCfecNumeroCfecSecondaire', $myObject->getIcfCfecNumeroCfecSecondaire());
        $this->assertEquals('MyIcfiUrlPrincipal', $myObject->getIcfCiUrlPrincipal());
        $this->assertEquals('MyIcfiRepTransferPrincipal', $myObject->getIcfCiRepTransferPrincipal());
        $this->assertEquals('MyIcfiRepCrPrincipal', $myObject->getIcfCiRepCrPrincipal());
        $this->assertEquals('MyIcfCiUrlSecondaire', $myObject->getIcfCiUrlSecondaire());
        $this->assertEquals('MyIcfiRepTransferSecondaire', $myObject->getIcfCiRepTransferSecondaire());
        $this->assertEquals('MyIcfCiRepCrSecondaire', $myObject->getIcfCiRepCrSecondaire());

        $this->assertEquals($now->getTimestamp(), $myObject->getIcfCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIcfUpdatedAt()->getTimestamp());
    }
}
