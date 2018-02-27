<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\StoStockage;

class StoStockageTest extends \PHPUnit_Framework_TestCase
{
    public function testStoStockageEntity()
    {
        $myObject = new StoStockage();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setStoVolume('MyStoVolume');
        $myObject->setStoLibelle('MyStoLibelle');
        $myObject->setStoChemin('MyStoChemin');
        $myObject->setStoCodeClient('MyStoCodeClient');
        $myObject->setStoCodeApplication('MyStoCodeApplication');
        $myObject->setStoUseVis(true);
        $myObject->setStoType('MyStoType');
        $myObject->setStoCfecBase('MyStoCfecBase');
        $myObject->setStoCfecCert('MyStoCfecCert');
        $myObject->setStoCreatedAt($now);
        $myObject->setStoUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getStoId());
        $this->assertEquals('MyStoVolume', $myObject->getStoVolume());
        $this->assertEquals('MyStoLibelle', $myObject->getStoLibelle());
        $this->assertEquals('MyStoChemin', $myObject->getStoChemin());
        $this->assertEquals('MyStoCodeClient', $myObject->getStoCodeClient());
        $this->assertEquals('MyStoCodeApplication', $myObject->getStoCodeApplication());
        $this->assertEquals(true, $myObject->isStoUseVis());
        $this->assertEquals('MyStoType', $myObject->getStoType());
        $this->assertEquals('MyStoCfecBase', $myObject->getStoCfecBase());
        $this->assertEquals('MyStoCfecCert', $myObject->getStoCfecCert());
        $this->assertEquals($now->getTimestamp(), $myObject->getStoCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getStoUpdatedAt()->getTimestamp());
    }
}