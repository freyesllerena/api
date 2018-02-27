<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\IucInteruploadCfg;

class IucInteruploadCfgTest extends \PHPUnit_Framework_TestCase
{
    public function testIucInteruploadCfgEntity()
    {
        $myObject = new IucInteruploadCfg();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setIucIdInterupload('MyIucIdInterupload');
        $myObject->setIucCodeclient('MyIucCodeClient');
        $myObject->setIucCodeappli('MyIucCodeappli');
        $myObject->setIucParam('MyIucParam');
        $myObject->setIucConfig('MyIucConfig');
        $myObject->setIucScriptArchivageSpecifique('MyIucScriptArchivageSpecifique');
        $myObject->setIucInteruploadweb('MyIucInteruploadweb');
        $myObject->setIucVersionapplet('MyIucVersionapplet');
        $myObject->setIucIdUpload('MyIucIdUpload');
        $myObject->setIucCreatedAt($now);
        $myObject->setIucUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getIucId());
        $this->assertEquals('MyIucIdInterupload', $myObject->getIucIdInterupload());
        $this->assertEquals('MyIucCodeClient', $myObject->getIucCodeclient());
        $this->assertEquals('MyIucCodeappli', $myObject->getIucCodeappli());
        $this->assertEquals('MyIucParam', $myObject->getIucParam());
        $this->assertEquals('MyIucConfig', $myObject->getIucConfig());
        $this->assertEquals('MyIucScriptArchivageSpecifique', $myObject->getIucScriptArchivageSpecifique());
        $this->assertEquals('MyIucInteruploadweb', $myObject->getIucInteruploadweb());
        $this->assertEquals('MyIucVersionapplet', $myObject->getIucVersionapplet());
        $this->assertEquals('MyIucIdUpload', $myObject->getIucIdUpload());
        $this->assertEquals($now->getTimestamp(), $myObject->getIucCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getIucUpdatedAt()->getTimestamp());
    }
}
