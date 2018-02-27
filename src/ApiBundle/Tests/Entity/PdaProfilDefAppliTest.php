<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\PdaProfilDefAppli;

class PdaProfilDefAppliTest extends \PHPUnit_Framework_TestCase
{
    public function testPdaProfilDefAppliEntity()
    {
        $myObject = new PdaProfilDefAppli();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setPdaLibelle('MyPdaLibelle');
        $myObject->setPdaRefBve('MyPdaRefBve');
        $myObject->setPdaNbi(2);
        $myObject->setPdaNbc(3);
        $myObject->setPdaAdp(true);
        $myObject->setPdaCreatedAt($now);
        $myObject->setPdaUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getPdaId());
        $this->assertEquals('MyPdaLibelle', $myObject->getPdaLibelle());
        $this->assertEquals('MyPdaRefBve', $myObject->getPdaRefBve());
        $this->assertEquals(2, $myObject->getPdaNbi());
        $this->assertEquals(3, $myObject->getPdaNbc());
        $this->assertEquals(true, $myObject->isPdaAdp());
        $this->assertEquals($now->getTimestamp(), $myObject->getPdaCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getPdaUpdatedAt()->getTimestamp());
    }
}