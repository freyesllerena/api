<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\IupInterupload;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIupInteruploadData extends AbstractFixture implements OrderedFixtureInterface
{
    static public $iupInterupload = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Ajout d'un ticket avec statut ER_DP_TraitementDesDocuments
        $iup01 = $this->buildIup01();
        $manager->persist($iup01);

        // Ajout d'un ticket avec statut OK_PRODUCTION
        $iup02 = $this->buildIup02();
        $manager->persist($iup02);

        // Ajout d'un ticket avec statut OK_ARCHIVAGE
        $iup03 = $this->buildIup03();
        $manager->persist($iup03);

        $manager->flush();

    }

    /**
     * Ajout d'un ticket IupInterupload
     * @return IupInterupload
     */
    private function buildIup01()
    {
        /**
         * Ajout du ticket 9f8d08f4397169b9c50e8d339a48137f142976c2fb376cdecaebdae5335db6a12b8a8d23
         * et le iup_statut 'ER_DP_TraitementDesDocuments --> ERREURTraitementDesDocuments --> ERREUR22 : Méthode introuvable : \'
         */
        $iup = new IupInterupload();
        $iup->setIupTicket("99d4bc8f6a14cc32bce11ef02fda5caff6869f60f753b62f0cc728938ea285b5c6271e27");
        $iup->setIupChallenge("");
        $iup->setIupStatut("OK_IR_WAIT");
        $iup->setIupDateProduction(null);
        $iup->setIupDateArchivage(null);
        $iup->setIupConfig('<config><traitements><traitement type="pdf/a" compression = "normal" toutenun= "oui"><![CDATA[*.doc;*.xls;*.zip;*.pdf;*.docx;*.xlsx;*.msg;*.csv;*.ppt;*.pptx;*.axx;*.tif;*.tiff ;*.jpg ;*.jpeg ;]]> </traitement></traitements><document><depot>  <type>depotcefc</type><url>https://192.168.205.15/cfec/</url>     <salle>1</salle>    <coffre>179</coffre>   <certificat><![CDATA[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]]></certificat>  <VolumeStockageImagerie>INTERUPLOAD</VolumeStockageImagerie> <PrefixImagerie>12</PrefixImagerie> </depot></document></config>');
        $iup->setIupMetadataCreation('<?xml version="1.0"?>
<metadata><enreg>41716</enreg><table_indexfiche>indexfiche_paperless_import</table_indexfiche><interupload_login>expertms_DPS</interupload_login><interupload_nom></interupload_nom><interupload_prenom></interupload_prenom><interupload_adresse_mail></interupload_adresse_mail><interupload_date>01-03-2016 14:01:04</interupload_date></metadata>
');
        $iup->setIupMetadataProduction("<metadata><document><type> </type><pages> </pages><taille> </taille><location> </location><filename> </filename><filename_sans_extension> </filename_sans_extension><filename_original>56d592910c87b_test.pdf</filename_original><VolumeStockageImagerie> </VolumeStockageImagerie></document></metadata>");
        $iup->setIupMetadataArchivage("OK");

        return $iup;
    }

    /**
     * Ajout d'un ticket où le serveur Interupload repond OK_PRODUCTION
     * @return IupInterupload
     */
    private function buildIup02()
    {
        /**
         * Ajout du ticket f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be
         */
        $iup = new IupInterupload();
        $iup->setIupTicket("f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be");
        $iup->setIupChallenge("");
        $iup->setIupStatut("OK_PRODUCTION");
        $iup->setIupDateProduction(null);
        $iup->setIupDateArchivage(null);
        $iup->setIupConfig('<config><traitements><traitement type="pdf/a" compression = "normal" toutenun= "oui"><![CDATA[*.doc;*.xls;*.zip;*.pdf;*.docx;*.xlsx;*.msg;*.csv;*.ppt;*.pptx;*.axx;*.tif;*.tiff ;*.jpg ;*.jpeg ;]]> </traitement></traitements><document><depot>  <type>depotcefc</type><url>https://192.168.205.15/cfec/</url>     <salle>1</salle>    <coffre>179</coffre>   <certificat><![CDATA[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]]></certificat>  <VolumeStockageImagerie>INTERUPLOAD</VolumeStockageImagerie> <PrefixImagerie>12</PrefixImagerie> </depot></document></config>');
        $iup->setIupMetadataCreation('<?xml version="1.0"?>
<metadata><enreg>41716</enreg><table_indexfiche>indexfiche_paperless_import</table_indexfiche><interupload_login>expertms_DPS</interupload_login><interupload_nom></interupload_nom><interupload_prenom></interupload_prenom><interupload_adresse_mail></interupload_adresse_mail><interupload_date>01-03-2016 14:01:04</interupload_date></metadata>
');
        $iup->setIupMetadataProduction("<metadata><document><type> </type><pages> </pages><taille> </taille><location> </location><filename> </filename><filename_sans_extension> </filename_sans_extension><filename_original>56d592910c87b_test.pdf</filename_original><VolumeStockageImagerie> </VolumeStockageImagerie></document></metadata>");
        $iup->setIupMetadataArchivage("OK");

        return $iup;
    }

    /**
     * Ajout d'un ticket dont le document est déjà archivé
     * @return IupInterupload
     */
    private function buildIup03()
    {
        /**
         * Ajout du ticket ce095f7b237bc10c8f126bc9157477412ff3eb3f3c55bbf70829cc1ba3fe063ef18e410b
         */
        $iup = new IupInterupload();
        $iup->setIupTicket("ce095f7b237bc10c8f126bc9157477412ff3eb3f3c55bbf70829cc1ba3fe063ef18e410b");
        $iup->setIupChallenge("");
        $iup->setIupStatut("OK_ARCHIVAGE");
        $iup->setIupDateProduction(null);
        $iup->setIupDateArchivage(null);
        $iup->setIupConfig('<config><traitements><traitement type="pdf/a" compression = "normal" toutenun= "oui"><![CDATA[*.doc;*.xls;*.zip;*.pdf;*.docx;*.xlsx;*.msg;*.csv;*.ppt;*.pptx;*.axx;*.tif;*.tiff ;*.jpg ;*.jpeg ;]]> </traitement></traitements><document><depot>  <type>depotcefc</type><url>https://192.168.205.15/cfec/</url>     <salle>1</salle>    <coffre>179</coffre>   <certificat><![CDATA[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]]></certificat>  <VolumeStockageImagerie>INTERUPLOAD</VolumeStockageImagerie> <PrefixImagerie>12</PrefixImagerie> </depot></document></config>');
        $iup->setIupMetadataCreation('<?xml version="1.0"?>
<metadata><enreg>41716</enreg><table_indexfiche>indexfiche_paperless_import</table_indexfiche><interupload_login>expertms_DPS</interupload_login><interupload_nom></interupload_nom><interupload_prenom></interupload_prenom><interupload_adresse_mail></interupload_adresse_mail><interupload_date>01-03-2016 14:01:04</interupload_date></metadata>
');
        $iup->setIupMetadataProduction("<metadata><document><type> </type><pages> </pages><taille> </taille><location> </location><filename> </filename><filename_sans_extension> </filename_sans_extension><filename_original>56d592910c87b_test.pdf</filename_original><VolumeStockageImagerie> </VolumeStockageImagerie></document></metadata>");
        $iup->setIupMetadataArchivage("OK");

        return $iup;
    }

    public function getOrder()
    {
        return 10;
    }
}
