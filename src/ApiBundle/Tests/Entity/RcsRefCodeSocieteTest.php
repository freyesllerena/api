<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\RcsRefCodeSociete;

class RcsRefCodeSocieteTest extends \PHPUnit_Framework_TestCase
{
    public function testRcsRefCodeSociete()
    {
        $myObject = new RcsRefCodeSociete();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setRcsCode('MyRcsCode');
        $myObject->setRcsLibelle('MyRcsLibelle');
        $myObject->setRcsSiren('123456789');
        $myObject->setRcsIdCodeClient('MyRcsIdCodeClient');
        $myObject->setRcsCreatedAt($now);
        $myObject->setRcsUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getRcsId());
        $this->assertEquals('MyRcsCode', $myObject->getRcsCode());
        $this->assertEquals('MyRcsLibelle', $myObject->getRcsLibelle());
        $this->assertEquals('123456789', $myObject->getRcsSiren());
        $this->assertEquals('MyRcsIdCodeClient', $myObject->getRcsIdCodeClient());
        $this->assertEquals($now->getTimestamp(), $myObject->getRcsCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getRcsUpdatedAt()->getTimestamp());
    }
}
