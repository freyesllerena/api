<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\MaiMails;

class MaiMailsTest extends \PHPUnit_Framework_TestCase
{
    public function testMaiMailsEntity()
    {
        $myObject = new MaiMails();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');
        $date1 = new \DateTime();
        $date1 = $date1->modify('-2H');
        $date2 = new \DateTime();
        $date2 = $date2->modify('-3H');

        // Test setter
        $myObject->setMaiUserLogin('MyMaiUserLogin');
        $myObject->setMaiMailFrom('MyMaiMailFrom');
        $myObject->setMaiMailTo('MyMaiMailTo');
        $myObject->setMaiMailCopy('MyMaiMailCopy');
        $myObject->setMaiMailHiddenCopy('MyMaiMailHiddenCopy');
        $myObject->setMaiMailNotify(true);
        $myObject->setMaiMailCopyToSender('MyMaiMailCopyToSender');
        $myObject->setMaiMailSubject('MyMaiMailSubject');
        $myObject->setMaiMailMessage('MyMaiMailMessage');
        $myObject->setMaiMailDate($date1);
        $myObject->setMaiMailTime($date2);
        $myObject->setMaiCreatedAt($now);
        $myObject->setMaiUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getMaiId());
        $this->assertEquals('MyMaiUserLogin', $myObject->getMaiUserLogin());
        $this->assertEquals('MyMaiMailFrom', $myObject->getMaiMailFrom());
        $this->assertEquals('MyMaiMailTo', $myObject->getMaiMailTo());
        $this->assertEquals('MyMaiMailCopy', $myObject->getMaiMailCopy());
        $this->assertEquals('MyMaiMailHiddenCopy', $myObject->getMaiMailHiddenCopy());
        $this->assertEquals(true, $myObject->isMaiMailNotify());
        $this->assertEquals('MyMaiMailCopyToSender', $myObject->isMaiMailCopyToSender());
        $this->assertEquals('MyMaiMailSubject', $myObject->getMaiMailSubject());
        $this->assertEquals('MyMaiMailMessage', $myObject->getMaiMailMessage());
        $this->assertEquals($date1->getTimestamp(), $myObject->getMaiMailDate()->getTimestamp());
        $this->assertEquals($date2->getTimestamp(), $myObject->getMaiMailTime()->getTimestamp());
        $this->assertEquals($now->getTimestamp(), $myObject->getMaiCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getMaiUpdatedAt()->getTimestamp());
    }
}
