<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\IhaImportHabilitation;
use ApiBundle\Entity\RapRapport;

class IhaImportHabilitationTest extends \PHPUnit_Framework_TestCase
{
    public function testIhaImportHabilitationEntity()
    {
        $myObject = new IhaImportHabilitation();
        $myRapport = new RapRapport();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setIhaTraite(6);
        $myObject->setIhaSucces(5);
        $myObject->setIhaErreur(1);
        $myObject->setIhaRapport($myRapport);
        $myObject->setIhaCreatedAt($now);
        $myObject->setIhaUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIhaId());
        $this->assertEquals(6, $myObject->getIhaTraite());
        $this->assertEquals(5, $myObject->getIhaSucces());
        $this->assertEquals(1, $myObject->getIhaErreur());
        $this->assertInstanceOf('ApiBundle\Entity\RapRapport', $myObject->getIhaRapport());
        $this->assertEquals($now->getTimestamp(), $myObject->getIhaCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIhaUpdatedAt()->getTimestamp());
    }
}
