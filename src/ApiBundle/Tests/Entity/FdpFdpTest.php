<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\FdpFdp;

class FdpFdpTest extends \PHPUnit_Framework_TestCase
{
    public function testFdpFdpEntity()
    {
        $myObject = new FdpFdp();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setFdpNom('MyFdpNom');
        $myObject->setFdpIdApplication("MyFdpIdApplication");
        $myObject->setFdpIdFdp("MyFdpIdFdp");
        $myObject->setFdpCheminOn("MyFdpCheminOn");
        $myObject->setFdpCheminOff("MyFdpCheminOff");
        $myObject->setFdpX(111);
        $myObject->setFdpY(222);
        $myObject->setFdpScaleX(333);
        $myObject->setFdpScaleY(444);
        $myObject->setFdpRotation(555);
        $myObject->setFdpCgv('MyFdpCgv');
        $myObject->setFdpAsCheminOn('MyFdpAsCheminOn');
        $myObject->setFdpAsX(11.11);
        $myObject->setFdpAsY(22.22);
        $myObject->setFdpAsScaleX(666);
        $myObject->setFdpAsScaleY(777);
        $myObject->setFdpAsRotation(888);
        $myObject->setFdpCreatedAt($now);
        $myObject->setFdpUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getFdpId());
        $this->assertEquals('MyFdpNom', $myObject->getFdpNom());
        $this->assertEquals('MyFdpIdApplication', $myObject->getFdpIdApplication());
        $this->assertEquals('MyFdpIdFdp', $myObject->getFdpIdFdp());
        $this->assertEquals('MyFdpCheminOn', $myObject->getFdpCheminOn());
        $this->assertEquals('MyFdpCheminOff', $myObject->getFdpCheminOff());
        $this->assertEquals(111, $myObject->getFdpX());
        $this->assertEquals(222, $myObject->getFdpY());
        $this->assertEquals(333, $myObject->getFdpScaleX());
        $this->assertEquals(444, $myObject->getFdpScaleY());
        $this->assertEquals(555, $myObject->getFdpRotation());
        $this->assertEquals('MyFdpCgv', $myObject->getFdpCgv());
        $this->assertEquals('MyFdpAsCheminOn', $myObject->getFdpAsCheminOn());
        $this->assertEquals(11.11, $myObject->getFdpAsX());
        $this->assertEquals(22.22, $myObject->getFdpAsY());
        $this->assertEquals(666, $myObject->getFdpAsScaleX());
        $this->assertEquals(777, $myObject->getFdpAsScaleY());
        $this->assertEquals(888, $myObject->getFdpAsRotation());
        $this->assertEquals($now->getTimestamp(), $myObject->getFdpCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getFdpUpdatedAt()->getTimestamp());
    }
}
