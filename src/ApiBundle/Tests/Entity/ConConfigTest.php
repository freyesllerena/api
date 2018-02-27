<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ConConfig;

class ConConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConConfigEntity()
    {
        $myObject = new ConConfig();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setConVariable('myConVariable');
        $myObject->setConValeur('myConValeur');
        $myObject->setConLabel('myConLabel');
        $myObject->setConCreatedAt($now);
        $myObject->setConUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getConId());
        $this->assertEquals('myConVariable', $myObject->getConVariable());
        $this->assertEquals('myConValeur', $myObject->getConValeur());
        $this->assertEquals('myConLabel', $myObject->getConLabel());
        $this->assertEquals($now->getTimestamp(), $myObject->getConCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getConUpdatedAt()->getTimestamp());
    }
}
