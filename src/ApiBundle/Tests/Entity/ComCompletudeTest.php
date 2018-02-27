<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\ComCompletude;
use ApiBundle\Entity\UsrUsers;

class ComCompletudeTest extends \PHPUnit_Framework_TestCase
{
    public function testComCompletudeEntity()
    {
        $myObject = new ComCompletude();
        $myUser = new UsrUsers();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setComUser($myUser);
        $myObject->setComLibelle('MyComLibelle');
        $myObject->setComPrivee(1);
        $myObject->setComAuto(0);
        $myObject->setComEmail('MyComEmail');
        $myObject->setComPeriode('MyComPeriode');
        $myObject->setComAvecDocuments(0);
        $myObject->setComPopulation('MyComPopulation');
        $myObject->setComNotification('MyComNotification');
        $myObject->setComCreatedAt($now);
        $myObject->setComUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getComIdCompletude());
        $this->assertInstanceOf('ApiBundle\Entity\UsrUsers', $myObject->getComUser());
        $this->assertEquals('MyComLibelle', $myObject->getComLibelle());
        $this->assertEquals(1, $myObject->isComPrivee());
        $this->assertEquals(0, $myObject->isComAuto());
        $this->assertEquals('MyComEmail', $myObject->getComEmail());
        $this->assertEquals('MyComPeriode', $myObject->getComPeriode());
        $this->assertEquals(0, $myObject->isComAvecDocuments());
        $this->assertEquals('MyComPopulation', $myObject->getComPopulation());
        $this->assertEquals('MyComNotification', $myObject->getComNotification());
        $this->assertEquals($now->getTimestamp(), $myObject->getComCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getComUpdatedAt()->getTimestamp());
    }
}
