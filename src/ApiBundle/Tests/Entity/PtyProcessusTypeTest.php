<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ProProcessus;
use ApiBundle\Entity\PtyProcessusType;
use ApiBundle\Entity\TypType;

class PtyProcessusTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testPtyProcessusTypeEntity()
    {
        $myObject = new PtyProcessusType();
        $myProcessus = new ProProcessus();
        $myType = new TypType();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setPtyProcessus($myProcessus);
        $myObject->setPtyType($myType);
        $myObject->setPtyCreatedAt($now);
        $myObject->setPtyUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getPtyId());
        $this->assertInstanceOf('ApiBundle\Entity\ProProcessus', $myObject->getPtyProcessus());
        $this->assertInstanceOf('ApiBundle\Entity\TypType', $myObject->getPtyType());
        $this->assertEquals($now->getTimestamp(), $myObject->getPtyCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getPtyUpdatedAt()->getTimestamp());
    }
}
