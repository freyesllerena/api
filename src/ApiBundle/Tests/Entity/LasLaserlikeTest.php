<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\LasLaserlike;

class LasLaserlikeTest extends \PHPUnit_Framework_TestCase
{
    public function testLasLaserlikeEntity()
    {
        $myObject = new LasLaserlike();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setLasDebut('MyLasDebut');
        $myObject->setLasFin('MyLasFin');
        $myObject->setLasChemin('MyLasChemin');
        $myObject->setLasLibelle('MyLasLibelle');
        $myObject->setLasIdApplication('MyLasIdApplication');
        $myObject->setLasType('MyLasType');
        $myObject->setLasCfecBase('MyLasCfecBase');
        $myObject->setLasCfecCert('MyLasCfecCert');
        $myObject->setLasCreatedAt($now);
        $myObject->setLasUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getLasId());
        $this->assertEquals('MyLasDebut', $myObject->getLasDebut());
        $this->assertEquals('MyLasFin', $myObject->getLasFin());
        $this->assertEquals('MyLasChemin', $myObject->getLasChemin());
        $this->assertEquals('MyLasLibelle', $myObject->getLasLibelle());
        $this->assertEquals('MyLasIdApplication', $myObject->getLasIdApplication());
        $this->assertEquals('MyLasType', $myObject->getLasType());
        $this->assertEquals('MyLasCfecBase', $myObject->getLasCfecBase());
        $this->assertEquals('MyLasCfecCert', $myObject->getLasCfecCert());
        $this->assertEquals($now->getTimestamp(), $myObject->getLasCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getLasUpdatedAt()->getTimestamp());
    }
}
