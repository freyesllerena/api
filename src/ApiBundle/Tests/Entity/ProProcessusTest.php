<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ProProcessus;
use ApiBundle\Entity\UsrUsers;

class ProProcessusTest extends \PHPUnit_Framework_TestCase
{
    public function testProProcessusEntity()
    {
        $myObject = new ProProcessus();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setProGroupe(1);
        $myObject->setProLibelle('MyProLibelle');
        $myObject->setProUser($myUser);
        $myObject->setProCreatedAt($now);
        $myObject->setProUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getProId());
        $this->assertEquals(1, $myObject->getProGroupe());
        $this->assertEquals('MyProLibelle', $myObject->getProLibelle());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getProUser());
        $this->assertEquals($now->getTimestamp(), $myObject->getProCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getProUpdatedAt()->getTimestamp());
    }
}
