<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\NlgNavLog;

class NlgNavLogTest extends \PHPUnit_Framework_TestCase
{
    public function testNlgNavLogEntity()
    {
        $myObject = new NlgNavLog();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setNlgNav('MyNlgNav');
        $myObject->setNlgUser('MyNlgUser');
        $myObject->setNlgGroupe('MyNlgGroupe');
        $myObject->setNlgOrsid('MyNlgOrsid');
        $myObject->setNlgPages('MyNlgPages');
        $myObject->setNlgOctets('MyNlgOctets');
        $myObject->setNlgIdSession('MyNlgIdSession');
        $myObject->setNlgInfo('MyNlgInfo');
        $myObject->setNlgAdresseIp('MyNlgAdresseIp');
        $myObject->setNlgUserAgent('MyNlgUserAgent');
        $myObject->setNlgFacturable(true);
        $myObject->setNlgCreatedAt($now);
        $myObject->setNlgUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getNlgId());
        $this->assertEquals('MyNlgNav', $myObject->getNlgNav());
        $this->assertEquals('MyNlgUser', $myObject->getNlgUser());
        $this->assertEquals('MyNlgGroupe', $myObject->getNlgGroupe());
        $this->assertEquals('MyNlgOrsid', $myObject->getNlgOrsid());
        $this->assertEquals('MyNlgPages', $myObject->getNlgPages());
        $this->assertEquals('MyNlgOctets', $myObject->getNlgOctets());
        $this->assertEquals('MyNlgIdSession', $myObject->getNlgIdSession());
        $this->assertEquals('MyNlgInfo', $myObject->getNlgInfo());
        $this->assertEquals('MyNlgAdresseIp', $myObject->getNlgAdresseIp());
        $this->assertEquals('MyNlgUserAgent', $myObject->getNlgUserAgent());
        $this->assertEquals(true, $myObject->isNlgFacturable());
        $this->assertEquals($now->getTimestamp(), $myObject->getNlgCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getNlgUpdatedAt()->getTimestamp());
    }
}
