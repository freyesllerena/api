<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ComCompletude;
use ApiBundle\Entity\CtyCompletudeType;
use ApiBundle\Entity\TypType;

class CtyCompletudeTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testCtyCompletudeTypeEntity()
    {
        $myObject = new CtyCompletudeType();
        $myCompletude = new ComCompletude();
        $myType = new TypType();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setCtyCompletude($myCompletude);
        $myObject->setCtyType($myType);
        $myObject->setCtyCreatedAt($now);
        $myObject->setCtyUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getCtyId());
        $this->assertInstanceOf('ApiBundle\Entity\ComCompletude', $myObject->getCtyCompletude());
        $this->assertInstanceOf('ApiBundle\Entity\TypType', $myObject->getCtyType());
        $this->assertEquals($now->getTimestamp(), $myObject->getCtyCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getCtyUpdatedAt()->getTimestamp());
    }
}
