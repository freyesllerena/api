<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\RidRefId;

class RidRefIdTest extends \PHPUnit_Framework_TestCase
{
    public function testRidRefIdEntity()
    {
        $myObject = new RidRefId();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setRidCode('MyRidCode');
        $myObject->setRidIdCodeClient('MyRidIdCodeClient');
        $myObject->setRidType('MyRidType');
        $myObject->setRidLibelle('MyRidLibelle');
        $myObject->setRidCreatedAt($now);
        $myObject->setRidUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getRidId());
        $this->assertEquals('MyRidCode', $myObject->getRidCode());
        $this->assertEquals('MyRidIdCodeClient', $myObject->getRidIdCodeClient());
        $this->assertEquals('MyRidType', $myObject->getRidType());
        $this->assertEquals('MyRidLibelle', $myObject->getRidLibelle());
        $this->assertEquals($now->getTimestamp(), $myObject->getRidCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getRidUpdatedAt()->getTimestamp());
    }
}
