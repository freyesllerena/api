<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Enum\EnumLabelModeHabilitationType;

class PdhProfilDefHabiTest extends \PHPUnit_Framework_TestCase
{
    public function testPdhProfilDefHabiEntity()
    {
        $myObject = new PdhProfilDefHabi();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test Setter
        $myObject->setPdhLibelle('MyPdhLibelle');
        $myObject->setPdhHabilitationI('MyPdhHabilitationI');
        $myObject->setPdhHabilitationC('MyPdhHabilitationC');
        $myObject->setPdhMode(EnumLabelModeHabilitationType::REFERENCE_MODE);
        $myObject->setPdhAdp(true);
        $myObject->setPdhCreatedAt($now);
        $myObject->setPdhUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getPdhId());
        $this->assertEquals('MyPdhLibelle', $myObject->getPdhLibelle());
        $this->assertEquals('MyPdhHabilitationI', $myObject->getPdhHabilitationI());
        $this->assertEquals('MyPdhHabilitationC', $myObject->getPdhHabilitationC());
        $this->assertEquals(EnumLabelModeHabilitationType::REFERENCE_MODE, $myObject->getPdhMode());
        $this->assertEquals(true, $myObject->isPdhAdp());
        $this->assertEquals($now->getTimestamp(), $myObject->getPdhCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getPdhUpdatedAt()->getTimestamp());
        $this->assertEquals(true, $myObject->isPdhModeReference());
        $this->assertEquals(false, $myObject->isPdhModeArchive());
    }
}