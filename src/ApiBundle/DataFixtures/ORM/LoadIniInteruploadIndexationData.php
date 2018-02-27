<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Tests\Lib\DocapostToolsTest;
use ApiBundle\Entity\IniInteruploadIndexation;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIniInteruploadIndexationData extends AbstractFixture implements OrderedFixtureInterface
{
    static public $iniIupIndexation = array();

    private $tools;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Ajout d'un ticket
        $ini01 = $this->buildIni01();
        $manager->persist($ini01);

        // Ajout d'un tiket
        $ini02 = $this->buildIni02();
        $manager->persist($ini02);

        // Ajout d'un tiket dont le document est déjà archivé
        $ini03 = $this->buildIni03();
        $manager->persist($ini03);

        $manager->flush();
    }

    /**
     * Ajout du ticket Interupload indexation pour qui Interupload a repondu OK_PRODUCTION
     * @return IniInteruploadIndexation
     */
    private function buildIni01()
    {
        /* @var $tools DocapostToolsTest */
        $this->tools = new DocapostToolsTest();
        /**
         * Ajout du ticket f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be
         */
        $ini = new IniInteruploadIndexation();
        $ini->setIniTicket("f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be");
        $ini->setIniContent($this->tools->jsonMinify(
            '{
                "IdNumeroBoiteArchive": null,
                "category": "I",
                "idCategory": "D256900",
                "pathEtArchiveName": "MyUsrLogin01|1465307763759|test.pdf",
                "idUpload": "MyUsrLogin01|1465307763759",
                "IdLibelleDocument": "Changement de RIB_11111",
                "IdNumMatriculeRh": "01000002",
                "IdLibEtablissement": null,
                "IdNomSociete": null,
                "IdPeriodePaie": "06/2016",
                "typeImport": "U",
                "nameFile": "test"
            }'
        ));

        return $ini;
    }

    /**
     * Ajout du ticket Interupload indexation pour qui Interupload repond ER_DP
     * @return IniInteruploadIndexation
     */
    private function buildIni02()
    {
        /* @var $tools DocapostToolsTest */
        $this->tools = new DocapostToolsTest();
        /**
         * Ajout du ticket 99d4bc8f6a14cc32bce11ef02fda5caff6869f60f753b62f0cc728938ea285b5c6271e27
         */
        $ini = new IniInteruploadIndexation();
        $ini->setIniTicket("99d4bc8f6a14cc32bce11ef02fda5caff6869f60f753b62f0cc728938ea285b5c6271e27");
        $ini->setIniContent($this->tools->jsonMinify(
            '{
                "IdNumeroBoiteArchive": null,
                "category": "I",
                "idCategory": "D256900",
                "pathEtArchiveName": "MyUsrLogin01|1465307763759|test.pdf",
                "idUpload": "MyUsrLogin01|1465307763759",
                "IdLibelleDocument": "Changement de RIB_11111",
                "IdNumMatriculeRh": "01000002",
                "IdLibEtablissement": null,
                "IdNomSociete": null,
                "IdPeriodePaie": "06/2016",
                "typeImport": "U",
                "nameFile": "test"
            }'
        ));

        return $ini;
    }

    /**
     * Ajout d'un ticket Interupload d'indexation dont le document est déjà archivé
     * @return IniInteruploadIndexation
     */
    private function buildIni03()
    {
        /* @var $tools DocapostToolsTest */
        $this->tools = new DocapostToolsTest();
        /**
         * Ajout du ticket ce095f7b237bc10c8f126bc9157477412ff3eb3f3c55bbf70829cc1ba3fe063ef18e410b
         */
        $ini3 = new IniInteruploadIndexation();
        $ini3->setIniTicket("ce095f7b237bc10c8f126bc9157477412ff3eb3f3c55bbf70829cc1ba3fe063ef18e410b");
        $ini3->setIniContent($this->tools->jsonMinify(
            '{
                "IdNumeroBoiteArchive": null, 
                "category": "I",
                "idCategory": "D150010",
                "pathEtArchiveName": "MyUsrLogin01|1465307763759|test.pdf",
                "idUpload": "MyUsrLogin01|1465307763759",
                "IdLibelleDocument": "Changement de RIB_11111",
                "IdNumMatriculeRh": "01000002",
                "IdLibEtablissement": null,
                "IdNomSociete": null,
                "IdPeriodePaie": "06/2016",
                "typeImport": "U",
                "nameFile": "test"
            }'
        ));

         return $ini3;
    }

    public function getOrder()
    {
        return 20;
    }
}
