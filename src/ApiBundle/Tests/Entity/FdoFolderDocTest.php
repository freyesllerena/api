<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\FdoFolderDoc;
use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\IfpIndexfichePaperless;

class FdoFolderDocTest extends \PHPUnit_Framework_TestCase
{
    public function testFdoFolderDocEntity()
    {
        $myObject = new FdoFolderDoc();
        $myDoc = new IfpIndexfichePaperless();
        $myFolder = new FolFolder();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setFdoDoc($myDoc);
        $myObject->setFdoFolder($myFolder);
        $myObject->setFdoCreatedAt($now);
        $myObject->setFdoUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getFdoId());
        $this->assertInstanceOf('ApiBundle\Entity\IfpIndexfichePaperless', $myObject->getFdoDoc());
        $this->assertInstanceOf('ApiBundle\Entity\FolFolder', $myObject->getFdoFolder());
        $this->assertEquals($now->getTimestamp(), $myObject->getFdoCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getFdoUpdatedAt()->getTimestamp());
    }
}
