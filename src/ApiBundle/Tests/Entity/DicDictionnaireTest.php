<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\DicDictionnaire;

class DicDictionnaireTest extends \PHPUnit_Framework_TestCase
{
    public function testDicDictionnaireEntity()
    {
        $myObject = new DicDictionnaire();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');

        // Test setter
        $myObject->setDicSupport('MyDicSupport');
        $myObject->setDicCode('MyDicCode');
        $myObject->setDicValeur('MyDicValeur');
        $myObject->setDicOldVariable('MyDicOldVariable');
        $myObject->setDicOldValeur('MyDicOldValeur');
        $myObject->setDicOldProvenance('MyDicOldProvenance');
        $myObject->setDicCreatedAt($now);
        $myObject->setDicUpdatedAt($later);
        $myObject->setTranslatableLocale('en_EN');

        // Test Getter
        $this->assertEquals(null, $myObject->getDicId());
        $this->assertEquals('MyDicSupport', $myObject->getDicSupport());
        $this->assertEquals('MyDicCode', $myObject->getDicCode());
        $this->assertEquals('MyDicValeur', $myObject->getDicValeur());
        $this->assertEquals('MyDicOldVariable', $myObject->getDicOldVariable());
        $this->assertEquals('MyDicOldValeur', $myObject->getDicOldValeur());
        $this->assertEquals('MyDicOldProvenance', $myObject->getDicOldProvenance());
        $this->assertEquals($now->getTimestamp(), $myObject->getDicCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getDicUpdatedAt()->getTimestamp());
    }
}
