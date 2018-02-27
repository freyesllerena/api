<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\IupInterupload;

class IupInteruploadTest extends \PHPUnit_Framework_TestCase
{
    public function testIupInteruploadEntity()
    {
        $myObject = new IupInterupload();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');
        $date1 = new \DateTime();
        $date1 = $date1->modify('-12H');
        $date2 = new \DateTime();
        $date2 = $date2->modify('-10H');

        // Test setter
        $myObject->setIupTicket('MyIupTicket');
        $myObject->setIupChallenge('MyIupChallenge');
        $myObject->setIupStatut('MyIupStatut');
        $myObject->setIupDateProduction($date1);
        $myObject->setIupDateArchivage($date2);
        $myObject->setIupConfig('MyIupConfig');
        $myObject->setIupMetadataCreation('MyIupMetadataCreation');
        $myObject->setIupMetadataProduction('MyIupMetadataProduction');
        $myObject->setIupMetadataArchivage('MyIupMetadataArchivage');
        $myObject->setIupCreatedAt($now);
        $myObject->setIupUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIupId());
        $this->assertEquals('MyIupTicket', $myObject->getIupTicket());
        $this->assertEquals('MyIupChallenge', $myObject->getIupChallenge());
        $this->assertEquals('MyIupStatut', $myObject->getIupStatut());
        $this->assertEquals($date1->getTimestamp(), $myObject->getIupDateProduction()->getTimestamp());
        $this->assertEquals($date2->getTimestamp(), $myObject->getIupDateArchivage()->getTimestamp());
        $this->assertEquals('MyIupConfig', $myObject->getIupConfig());
        $this->assertEquals('MyIupMetadataCreation', $myObject->getIupMetadataCreation());
        $this->assertEquals('MyIupMetadataProduction', $myObject->getIupMetadataProduction());
        $this->assertEquals('MyIupMetadataArchivage', $myObject->getIupMetadataArchivage());
        $this->assertEquals($now->getTimestamp(), $myObject->getIupCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIupUpdatedAt()->getTimestamp());
    }
}
