<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\PdeProfilDef;

class PdeProfilDefTest extends \PHPUnit_Framework_TestCase
{
    public function testPdeProfilDefEntity()
    {
        $myObject = new PdeProfilDef();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setPdeId(2);
        $myObject->setPdeIdProfilDef(8);
        $myObject->setPdeType('MyPdeType');
        $myObject->setPdeCreatedAt($now);
        $myObject->setPdeUpdatedAt($later);

        // Test Getter
        $this->assertEquals(2, $myObject->getPdeId());
        $this->assertEquals(8, $myObject->getPdeIdProfilDef());
        $this->assertEquals('MyPdeType', $myObject->getPdeType());
        $this->assertEquals($now->getTimestamp(), $myObject->getPdeCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getPdeUpdatedAt()->getTimestamp());
    }
}