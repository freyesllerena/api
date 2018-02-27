<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\RapRapport;
use ApiBundle\Entity\UsrUsers;

class RapRapportTest extends \PHPUnit_Framework_TestCase
{
    public function testRapRapportEntity()
    {
        $myObject = new RapRapport();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setRapUser($myUser);
        $myObject->setRapTypeRapport('IMPORT');
        $myObject->setRapFichier('MyRapFichier');
        $myObject->setRapLibelleFic('MyRapLibelleFic');
        $myObject->setRapEtat('OK');
        $myObject->setRapCreatedAt($now);
        $myObject->setRapUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getRapId());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getRapUser());
        $this->assertEquals('IMPORT', $myObject->getRapTypeRapport());
        $this->assertEquals('MyRapFichier', $myObject->getRapFichier());
        $this->assertEquals('MyRapLibelleFic', $myObject->getRapLibelleFic());
        $this->assertEquals('OK', $myObject->getRapEtat());
        $this->assertEquals($now->getTimestamp(), $myObject->getRapCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getRapUpdatedAt()->getTimestamp());
    }
}
