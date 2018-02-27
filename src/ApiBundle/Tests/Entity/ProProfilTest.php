<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ProProfil;

class ProProfilTest extends \PHPUnit_Framework_TestCase
{
    public function testProProfilEntity()
    {
        $myObject = new ProProfil();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setProLibelle('MyProLibelle');
        $myObject->setProOrder(true);
        $myObject->setProImport(false);
        $myObject->setProGeneric(true);
        $myObject->setProAdp(false);
        $myObject->setProArc(false);
        $myObject->setProCreatedAt($now);
        $myObject->setProUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getProId());
        $this->assertEquals('MyProLibelle', $myObject->getProLibelle());
        $this->assertEquals(true, $myObject->isProOrder());
        $this->assertEquals(false, $myObject->isProImport());
        $this->assertEquals(true, $myObject->isProGeneric());
        $this->assertEquals(false, $myObject->isProAdp());
        $this->assertEquals(false, $myObject->isProArc());
        $this->assertEquals($now->getTimestamp(), $myObject->getProCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getProUpdatedAt()->getTimestamp());
    }
}
