<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\RceRefCodeEtablissement;
use ApiBundle\Entity\RcsRefCodeSociete;

class RceRefCodeEtablissementTest extends \PHPUnit_Framework_TestCase
{
    public function testRceRefCodeEtablissement()
    {
        $myObject = new RceRefCodeEtablissement();
        $myCompany = new RcsRefCodeSociete();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setRceCode('MyRceCode');
        $myObject->setRceLibelle('MyRceLibelle');
        $myObject->setRceNic('123');
        $myObject->setRceSociete($myCompany);
        $myObject->setRceCreatedAt($now);
        $myObject->setRceUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getRceId());
        $this->assertEquals('MyRceCode', $myObject->getRceCode());
        $this->assertEquals('MyRceLibelle', $myObject->getRceLibelle());
        $this->assertEquals('123', $myObject->getRceNic());
        $this->assertInstanceOf('ApiBundle\Entity\RcsRefCodeSociete', $myObject->getRceSociete());
        $this->assertEquals($now->getTimestamp(), $myObject->getRceCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getRceUpdatedAt()->getTimestamp());
    }
}
