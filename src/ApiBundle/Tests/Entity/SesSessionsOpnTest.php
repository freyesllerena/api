<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\SesSessionsOpn;

class SesSessionsOpnTest extends \PHPUnit_Framework_TestCase
{
    public function testSesSessionsOpnEntity()
    {
        $myObject = new SesSessionsOpn();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setSesIdSession('MySesIdSession');
        $myObject->setSesUserLogin('MySesUserLogin');
        $myObject->setSesMachine('MySesMachine');
        $myObject->setSesFirstAction(6666666666);
        $myObject->setSesLastAction(9999999999);
        $myObject->setSesVariables('MySesMachine');
        $myObject->setSesCreatedAt($now);
        $myObject->setSesUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getSesId());
        $this->assertEquals('MySesIdSession', $myObject->getSesIdSession());
        $this->assertEquals('MySesUserLogin', $myObject->getSesUserLogin());
        $this->assertEquals('MySesMachine', $myObject->getSesMachine());
        $this->assertEquals(6666666666, $myObject->getSesFirstAction());
        $this->assertEquals(9999999999, $myObject->getSesLastAction());
        $this->assertEquals('MySesMachine', $myObject->getSesVariables());
        $this->assertEquals($now->getTimestamp(), $myObject->getSesCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getSesUpdatedAt()->getTimestamp());
    }
}