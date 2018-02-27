<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\AsoAlerteSecuriteOpn;

class AsoAlerteSecuriteOpnTest extends \PHPUnit_Framework_TestCase
{
    public function testAsoAlerteSecuriteOpnEntity()
    {
        $myObject = new AsoAlerteSecuriteOpn();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setAsoReferer('MyAsoReferer');
        $myObject->setAsoMachine('MyAsoMachine');
        $myObject->setAsoRequestMethod('MyAsoRequestMethod');
        $myObject->setAsoRequestUri('MyAsoResquestUri');
        $myObject->setAsoCreatedAt($now);
        $myObject->setAsoUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getAsoId());
        $this->assertEquals('MyAsoReferer', $myObject->getAsoReferer());
        $this->assertEquals('MyAsoMachine', $myObject->getAsoMachine());
        $this->assertEquals('MyAsoRequestMethod', $myObject->getAsoRequestMethod());
        $this->assertEquals('MyAsoResquestUri', $myObject->getAsoRequestUri());
        $this->assertEquals($now->getTimestamp(), $myObject->getAsoCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getAsoUpdatedAt()->getTimestamp());
    }
}
