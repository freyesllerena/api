<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\La0Langue0;

class La0Langue0Test extends \PHPUnit_Framework_TestCase
{
    public function testLa0Langue0Entity()
    {
        $myObject = new La0Langue0();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setLa0Variable('MyLa0Variable');
        $myObject->setLa0Valeur('MyLa0Valeur');
        $myObject->setLa0CreatedAt($now);
        $myObject->setLa0UpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getLa0Id());
        $this->assertEquals('MyLa0Variable', $myObject->getLa0Variable());
        $this->assertEquals('MyLa0Valeur', $myObject->getLa0Valeur());
        $this->assertEquals($now->getTimestamp(), $myObject->getLa0CreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getLa0UpdatedAt()->getTimestamp());
    }
}
