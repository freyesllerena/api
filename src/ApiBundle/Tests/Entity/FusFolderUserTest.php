<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\FusFolderUser;
use ApiBundle\Entity\UsrUsers;

class FusFolderUserTest extends \PHPUnit_Framework_TestCase
{
    public function testFusFolderUserEntity()
    {
        $myObject = new FusFolderUser();
        $myFolder = new FolFolder();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');
        $access = new \DateTime();

        // Test setter
        $myObject->setFusFolder($myFolder);
        $myObject->setFusUser($myUser);
        $myObject->setFusDateAcces($access);
        $myObject->setFusCreatedAt($now);
        $myObject->setFusUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getFusId());
        $this->assertInstanceOf('ApiBundle\Entity\FolFolder', $myObject->getFusFolder());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getFusUser());
        $this->assertEquals($access->getTimestamp(), $myObject->getFusDateAcces()->getTimestamp());
        $this->assertEquals($now->getTimestamp(), $myObject->getFusCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getFusUpdatedAt()->getTimestamp());
    }
}
