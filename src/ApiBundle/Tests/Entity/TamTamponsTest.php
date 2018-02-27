<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\TamTampons;

class TamTamponsTest extends \PHPUnit_Framework_TestCase
{
    public function testTamTamponsEntity()
    {
        $myObject = new TamTampons();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setTamIdFdp('MyTamIdFdp');
        $myObject->setTamChemin('MyTamChemin');
        $myObject->setTamX(100);
        $myObject->setTamY(200);
        $myObject->setTamScaleX(30);
        $myObject->setTamScaleY(40);
        $myObject->setTamRotation(25);
        $myObject->setTamModeOverlay(false);
        $myObject->setTamAddFreeText('MyTamAddFreeText');
        $myObject->setTamAsX(50);
        $myObject->setTamAsY(60);
        $myObject->setTamAsScaleX(70);
        $myObject->setTamAsScaleY(80);
        $myObject->setTamAsRotation(35);
        $myObject->setTamAsModeOverlay(true);
        $myObject->setTamCreatedAt($now);
        $myObject->setTamUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getTamId());
        $this->assertEquals('MyTamIdFdp', $myObject->getTamIdFdp());
        $this->assertEquals('MyTamChemin', $myObject->getTamChemin());
        $this->assertEquals(100, $myObject->getTamX());
        $this->assertEquals(200, $myObject->getTamY());
        $this->assertEquals(30, $myObject->getTamScaleX());
        $this->assertEquals(40, $myObject->getTamScaleY());
        $this->assertEquals(25, $myObject->getTamRotation());
        $this->assertEquals(false, $myObject->isTamModeOverlay());
        $this->assertEquals('MyTamAddFreeText', $myObject->getTamAddFreeText());
        $this->assertEquals(50, $myObject->getTamAsX());
        $this->assertEquals(60, $myObject->getTamAsY());
        $this->assertEquals(70, $myObject->getTamAsScaleX());
        $this->assertEquals(80, $myObject->getTamAsScaleY());
        $this->assertEquals(35, $myObject->getTamAsRotation());
        $this->assertEquals(true, $myObject->isTamAsModeOverlay());
        $this->assertEquals($now->getTimestamp(), $myObject->getTamCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getTamUpdatedAt()->getTimestamp());
    }
}