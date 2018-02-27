<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ErrErreur;

class ErrErreurTest extends \PHPUnit_Framework_TestCase
{
    public function testErrErreurEntity()
    {
        $myObject = new ErrErreur();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setErrCode(9);
        $myObject->setErrMessage('MyErrMessage');
        $myObject->setErrServer('MyErrServer');
        $myObject->setErrCreatedAt($now);
        $myObject->setErrUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getErrId());
        $this->assertEquals(9, $myObject->getErrCode());
        $this->assertEquals('MyErrMessage', $myObject->getErrMessage());
        $this->assertEquals('MyErrServer', $myObject->getErrServer());
        $this->assertEquals($now->getTimestamp(), $myObject->getErrCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getErrUpdatedAt()->getTimestamp());
    }
}
