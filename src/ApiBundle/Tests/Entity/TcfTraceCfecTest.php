<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\TcfTraceCfec;

class TcfTraceCfecTest extends \PHPUnit_Framework_TestCase
{
    public function testTcfTraceCfecEntity()
    {
        $myObject = new TcfTraceCfec();
        $now = new \DateTime();
        $later = new \DateTime();
        $later = $later->modify('+1H');
        $date1 = new\DateTime();
        $date1 = $date1->modify('-1W');

        // Test Setter
        $myObject->setTcfNomFichier('MyTcfNomFichier');
        $myObject->setTcfNumFdr('MyTcfNumFdr');
        $myObject->setTcfVdmLocalisation('MyTcfVdmLocalisation');
        $myObject->setTcfNomLot('MyTcfNomLot');
        $myObject->setTcfIdentifiantUnique('MyTcfIdentifiantUnique');
        $myObject->setTcfEmpreinteArchivage('MyTcfEmpreinteArchivage');
        $myObject->setTcfArchiveNumCfe('MyTcfArchiveNumCfe');
        $myObject->setTcfArchiveNumCfec('MyTcfArchiveNumCfec');
        $myObject->setTcfArchiveCheminCfec('MyTcfArchiveCheminCfec');
        $myObject->setTcfArchiveSerialNumber('MyTcfArchiveSerialNumber');
        $myObject->setTcfArchiveSerialNumber2('MyTcfArchiveSerialNumber2');
        $myObject->setTcfArchiveDatetime('MyTcfArchiveDatetime');
        $myObject->setTcfCheminCrPec('MyTcfCheminCrPec');
        $myObject->setTcfDateDepot($date1);
        $myObject->setTcfCreatedAt($now);
        $myObject->setTcfUpdatedAt($later);

        // Test Getter
        $this->assertEquals(null, $myObject->getTcfId());
        $this->assertEquals('MyTcfNomFichier', $myObject->getTcfNomFichier());
        $this->assertEquals('MyTcfNumFdr', $myObject->getTcfNumFdr());
        $this->assertEquals('MyTcfVdmLocalisation', $myObject->getTcfVdmLocalisation());
        $this->assertEquals('MyTcfNomLot', $myObject->getTcfNomLot());
        $this->assertEquals('MyTcfIdentifiantUnique', $myObject->getTcfIdentifiantUnique());
        $this->assertEquals('MyTcfEmpreinteArchivage', $myObject->getTcfEmpreinteArchivage());
        $this->assertEquals('MyTcfArchiveNumCfe', $myObject->getTcfArchiveNumCfe());
        $this->assertEquals('MyTcfArchiveNumCfec', $myObject->getTcfArchiveNumCfec());
        $this->assertEquals('MyTcfArchiveCheminCfec', $myObject->getTcfArchiveCheminCfec());
        $this->assertEquals('MyTcfArchiveSerialNumber', $myObject->getTcfArchiveSerialNumber());
        $this->assertEquals('MyTcfArchiveSerialNumber2', $myObject->getTcfArchiveSerialNumber2());
        $this->assertEquals('MyTcfArchiveDatetime', $myObject->getTcfArchiveDatetime());
        $this->assertEquals('MyTcfCheminCrPec', $myObject->getTcfCheminCrPec());
        $this->assertEquals($date1->getTimestamp(), $myObject->getTcfDateDepot()->getTimestamp());
        $this->assertEquals($now->getTimestamp(), $myObject->getTcfCreatedAt()->getTimestamp());
        $this->assertEquals($later->getTimestamp(), $myObject->getTcfUpdatedAt()->getTimestamp());
    }
}