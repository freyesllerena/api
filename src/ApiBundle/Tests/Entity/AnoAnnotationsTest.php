<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\AnoAnnotations;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\UsrUsers;

class AnoAnnotationsTest extends \PHPUnit_Framework_TestCase
{
    public function testAnoAnnotationsEntity()
    {
        $myObject = new AnoAnnotations();
        $myDoc = new IfpIndexfichePaperless();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setAnoFiche($myDoc);
        $myObject->setAnoLogin($myUser);
        $myObject->setAnoEtat('MyAdoEtat');
        $myObject->setAnoTexte('MyAdoTexte');
        $myObject->setAnoStatut('MyAdoStatus');
        $myObject->setAnoCreatedAt($now);
        $myObject->setAnoUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getAnoId());
        $this->assertInstanceOf('ApiBundle\Entity\IfpIndexfichePaperless', $myObject->getAnoFiche());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getAnoLogin());
        $this->assertEquals('MyAdoEtat', $myObject->getAnoEtat());
        $this->assertEquals('MyAdoTexte', $myObject->getAnoTexte());
        $this->assertEquals('MyAdoStatus', $myObject->getAnoStatut());
        $this->assertEquals($now->getTimestamp(), $myObject->getAnoCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getAnoUpdatedAt()->getTimestamp());
    }
}
