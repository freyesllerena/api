<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\UprUserPreferences;
use ApiBundle\Entity\UsrUsers;

class UprUserPreferencesTest extends \PHPUnit_Framework_TestCase
{
    public function testUprUserPreferencesEntity()
    {
        $myObject = new UprUserPreferences();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setUprDevice('DES');
        $myObject->setUprType('MyUprType');
        $myObject->setUprData('MyUprData');
        $myObject->setUprUser($myUser);
        $myObject->setUprCreatedAt($now);
        $myObject->setUprUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getUprId());
        $this->assertEquals('DES', $myObject->getUprDevice());
        $this->assertEquals('MyUprType', $myObject->getUprType());
        $this->assertEquals('MyUprData', $myObject->getUprData());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getUprUser());
        $this->assertEquals($now->getTimestamp(), $myObject->getUprCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getUprUpdatedAt()->getTimestamp());
    }
}
