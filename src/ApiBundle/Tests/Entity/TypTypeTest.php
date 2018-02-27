<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\TypType;

class TypTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testTypTypeEntity()
    {
        $myObject = new TypType();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setTypCode('MyTypCode');
        $myObject->setTypCodeAdp('MyTypCodeAdp');
        $myObject->setTypIdNiveau('10210');
        $myObject->setTypType(2);
        $myObject->setTypIndividuel(true);
        $myObject->setTypVieDuree(30);
        $myObject->setTypNumOrdre1(1);
        $myObject->setTypNumOrdre2(2);
        $myObject->setTypNumOrdre3(3);
        $myObject->setTypNumOrdre4(4);
        $myObject->setTypDateEffet('MyTypDateEffet');
        $myObject->setTypCreatedAt($now);
        $myObject->setTypUpdatedAt($later);

        // Test Getter
        $this->assertEquals('MyTypCode', $myObject->getTypCode());
        $this->assertEquals('MyTypCodeAdp', $myObject->getTypCodeAdp());
        $this->assertEquals('10210', $myObject->getTypIdNiveau());
        $this->assertEquals(2, $myObject->getTypType());
        $this->assertEquals(true, $myObject->isTypIndividuel());
        $this->assertEquals(30, $myObject->getTypVieDuree());
        $this->assertEquals(1, $myObject->getTypNumOrdre1());
        $this->assertEquals(2, $myObject->getTypNumOrdre2());
        $this->assertEquals(3, $myObject->getTypNumOrdre3());
        $this->assertEquals(4, $myObject->getTypNumOrdre4());
        $this->assertEquals('MyTypDateEffet', $myObject->getTypDateEffet());
        $this->assertEquals($now->getTimestamp(), $myObject->getTypCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getTypUpdatedAt()->getTimestamp());
    }
}