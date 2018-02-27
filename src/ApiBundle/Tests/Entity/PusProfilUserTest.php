<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ProProfil;
use ApiBundle\Entity\PusProfilUser;
use ApiBundle\Entity\UsrUsers;

class PusProfilUserTest extends \PHPUnit_Framework_TestCase
{
    public function testPusProfilUserTestEntity()
    {
        $myObject = new PusProfilUser();
        $myProfil = new ProProfil();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setPusProfil($myProfil);
        $myObject->setPusUser($myUser);
        $myObject->setPusCreatedAt($now);
        $myObject->setPusUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getPusId());
        $this->assertInstanceOf('ApiBundle\Entity\ProProfil', $myObject->getPusProfil());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getPusUser());
        $this->assertEquals($now->getTimestamp(), $myObject->getPusCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getPusUpdatedAt()->getTimestamp());
    }
}
