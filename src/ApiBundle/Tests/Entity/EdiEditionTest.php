<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\EdiEdition;

class EdiEditionTest extends \PHPUnit_Framework_TestCase
{
    public function testEdiEditionEntity()
    {
        $myObject = new EdiEdition();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setEdiFiche(111);
        $myObject->setEdiUserLogin('myUserLogin');
        $myObject->setEdiCreatedAt($now);
        $myObject->setEdiUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getEdiId());
        $this->assertEquals(111, $myObject->getEdiFiche());
        $this->assertEquals('myUserLogin', $myObject->getEdiUserLogin());
        $this->assertEquals($now->getTimestamp(), $myObject->getEdiCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getEdiUpdatedAt()->getTimestamp());
    }
}
