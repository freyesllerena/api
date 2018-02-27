<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\TseTimeSession;

class TseTimeSessionTest extends \PHPUnit_Framework_TestCase
{
    public function testTseTimeSessionEntity()
    {
        $myObject = new TseTimeSession();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setTseIdSession('MyTseIdSession');
        $myObject->setTseUserLogin('MyTseUserLogin');
        $myObject->setTseFirstAction(9876543210);
        $myObject->setTseLastAction(1234567890);
        $myObject->setTseTimeSession(9999);
        $myObject->setTseCreatedAt($now);
        $myObject->setTseUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getTseId());
        $this->assertEquals('MyTseIdSession', $myObject->getTseIdSession());
        $this->assertEquals('MyTseUserLogin', $myObject->getTseUserLogin());
        $this->assertEquals(9876543210, $myObject->getTseFirstAction());
        $this->assertEquals(1234567890, $myObject->getTseLastAction());
        $this->assertEquals(9999, $myObject->getTseTimeSession());
        $this->assertEquals($now->getTimestamp(), $myObject->getTseCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getTseUpdatedAt()->getTimestamp());
    }
}