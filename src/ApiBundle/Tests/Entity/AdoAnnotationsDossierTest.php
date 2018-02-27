<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\AdoAnnotationsDossier;
use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\UsrUsers;

class AdoAnnotationsDossierTest extends \PHPUnit_Framework_TestCase
{
    public function testAdoAnnotationsDossierEntity()
    {
        $myObject = new AdoAnnotationsDossier();
        $myFolder = new FolFolder();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setAdoFolder($myFolder);
        $myObject->setAdoLogin($myUser);
        $myObject->setAdoEtat('MyAdoEtat');
        $myObject->setAdoTexte('MyAdoTexte');
        $myObject->setAdoStatut('MyAdoStatus');
        $myObject->setAdoCreatedAt($now);
        $myObject->setAdoUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getAdoId());
        $this->assertInstanceOf('ApiBundle\Entity\FolFolder', $myObject->getAdoFolder());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getAdoLogin());
        $this->assertEquals('MyAdoEtat', $myObject->getAdoEtat());
        $this->assertEquals('MyAdoTexte', $myObject->getAdoTexte());
        $this->assertEquals('MyAdoStatus', $myObject->getAdoStatut());
        $this->assertEquals($now->getTimestamp(), $myObject->getAdoCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getAdoUpdatedAt()->getTimestamp());
    }
}
