<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\CdvCycleDeVie;

class CdvCycleDeVieTest extends \PHPUnit_Framework_TestCase
{
    public function testCdvCycleDeVieEntity()
    {
        $myObject = new CdvCycleDeVie();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setCdvYyss('1602');
        $myObject->setCdvNbDocIndiv(9999);
        $myObject->setCdvNbDocCollect(8888);
        $myObject->setCdvCreatedAt($now);
        $myObject->setCdvUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getCdvId());
        $this->assertEquals('1602', $myObject->getCdvYyss());
        $this->assertEquals(9999, $myObject->getCdvNbDocIndiv());
        $this->assertEquals(8888, $myObject->getCdvNbDocCollect());
        $this->assertEquals($now->getTimestamp(), $myObject->getCdvCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getCdvUpdatedAt()->getTimestamp());
    }
}
