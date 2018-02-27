<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\IniInteruploadIndexation;

class IniInteruploadIndexationTest extends \PHPUnit_Framework_TestCase
{
    public function testIniInteruploadIndexationEntity()
    {
        $myObject = new IniInteruploadIndexation();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setIniTicket("MyIniTicket");
        $myObject->setIniContent('{"ifpIdNumMatriculeRh":"8000452","ifpLibelleDocument":"Fiche de paie"}');
        $myObject->setIniCreatedAt($now);
        $myObject->setIniUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIniId());
        $this->assertEquals("MyIniTicket", $myObject->getIniTicket());
        $this->assertEquals(
            '{"ifpIdNumMatriculeRh":"8000452","ifpLibelleDocument":"Fiche de paie"}',
            $myObject->getIniContent()
        );
        $this->assertEquals($now->getTimestamp(), $myObject->getIniCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIniUpdatedAt()->getTimestamp());
    }
}
