<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\PrtPrint;

class PrtPrintTest extends \PHPUnit_Framework_TestCase
{
    public function testPrtPrintEntity()
    {
        $myObject = new PrtPrint();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setPrtUserLogin('MyPrtUserLogin');
        $myObject->setPrtFiche(9999);
        $myObject->setPrtIdSession('MyPrtIdSession');
        $myObject->setPrtCreatedAt($now);
        $myObject->setPrtUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getPrtId());
        $this->assertEquals('MyPrtUserLogin', $myObject->getPrtUserLogin());
        $this->assertEquals(9999, $myObject->getPrtFiche());
        $this->assertEquals('MyPrtIdSession', $myObject->getPrtIdSession());
        $this->assertEquals($now->getTimestamp(), $myObject->getPrtCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getPrtUpdatedAt()->getTimestamp());
    }
}