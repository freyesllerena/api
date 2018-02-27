<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\La1Langue1;

class La1Langue1Test extends \PHPUnit_Framework_TestCase
{
    public function testLa1Langue1Entity()
    {
        $myObject = new La1Langue1();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setLa1Variable('MyLa1Variable');
        $myObject->setLa1Valeur('MyLa1Valeur');
        $myObject->setLa1CreatedAt($now);
        $myObject->setLa1UpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getLa1Id());
        $this->assertEquals('MyLa1Variable', $myObject->getLa1Variable());
        $this->assertEquals('MyLa1Valeur', $myObject->getLa1Valeur());
        $this->assertEquals($now->getTimestamp(), $myObject->getLa1CreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getLa1UpdatedAt()->getTimestamp());
    }
}
