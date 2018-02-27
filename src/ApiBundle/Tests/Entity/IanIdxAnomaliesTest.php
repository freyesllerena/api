<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\IanIdxAnomalies;

class IanIdxAnomaliesTest extends \PHPUnit_Framework_TestCase
{
    public function testPdhProfilDefHabiEntity()
    {
        $myObject = new IanIdxAnomalies();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setIanAnomalie('MyIanAnomalie');
        $myObject->setIanDate($now);
        $myObject->setIanRefAdp('MyIanRefAdp');
        $myObject->setIanType('MyIanType');
        $myObject->setIanMatricule('MyIanMatricule');
        $myObject->setIanEtatArchive('MyIanEtatArchive');
        $myObject->setIanFichier('MyIanFichier');
        $myObject->setIanIndiv(true);
        $myObject->setIanNomPrenom('MyIanNomPrenom');
        $myObject->setIanCreatedAt($now);
        $myObject->setIanUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIanId());
        $this->assertEquals('MyIanAnomalie', $myObject->getIanAnomalie());
        $this->assertEquals($now->getTimestamp(), $myObject->getIanDate()->getTimestamp());
        $this->assertEquals('MyIanRefAdp', $myObject->getIanRefAdp());
        $this->assertEquals('MyIanType', $myObject->getIanType());
        $this->assertEquals('MyIanMatricule', $myObject->getIanMatricule());
        $this->assertEquals('MyIanEtatArchive', $myObject->getIanEtatArchive());
        $this->assertEquals('MyIanFichier', $myObject->getIanFichier());
        $this->assertEquals(true, $myObject->isIanIndiv());
        $this->assertEquals('MyIanNomPrenom', $myObject->getIanNomPrenom());
        $this->assertEquals($now->getTimestamp(), $myObject->getIanCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIanUpdatedAt()->getTimestamp());
    }
}
