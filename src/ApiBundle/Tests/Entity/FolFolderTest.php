<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\FolFolder;

class FolFolderTest extends \PHPUnit_Framework_TestCase
{
    public function testFolFolderEntity()
    {
        $myObject = new FolFolder();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setFolLibelle('MyFolLibelle');
        $myObject->setFolIdOwner('MyFolIdOwner');
        // $myObject->setFolType('Typ');
        $myObject->setFolNbDoc(999);
        $myObject->setFolCreatedAt($now);
        $myObject->setFolUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getFolId());
        $this->assertEquals('MyFolLibelle', $myObject->getFolLibelle());
        $this->assertEquals('MyFolIdOwner', $myObject->getFolIdOwner());
        $this->assertEquals('FOL', $myObject->getFolType());
        $this->assertEquals(999, $myObject->getFolNbDoc());
        $this->assertEquals($now->getTimestamp(), $myObject->getFolCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getFolUpdatedAt()->getTimestamp());
    }
}
