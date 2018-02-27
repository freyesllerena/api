<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\IfpIndexfichePaperless;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIfpIndexfichePaperlessData extends AbstractFixture implements OrderedFixtureInterface
{
    static public $ifp = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Ajout d'un document individuel gelé pour le salarié ALLAIGRE CARINE
        $ifpPaperless01 = $this->buildIfp01();
        $manager->persist($ifpPaperless01);

        // Ajout d'un document individuel non gelé pour le salarié ACHENAIS STEPHANE
        $ifpPaperless02 = $this->buildIfp02();
        $manager->persist($ifpPaperless02);

        // Ajout d'un document individuel non gelé pour le salarié EUTEBERT EVELYNE
        $ifpPaperless03 = $this->buildIfp03();
        $manager->persist($ifpPaperless03);

        // Ajout d'un document collectif gelé
        $ifpPaperless04 = $this->buildIfp04();
        $manager->persist($ifpPaperless04);

        // Ajout d'un document collectif non gelé
        $ifpPaperless05 = $this->buildIfp05();
        $manager->persist($ifpPaperless05);

        // Ajout d'un document collectif non gelé et non supprimé
        $ifpPaperless06 = $this->buildIfp06();
        $manager->persist($ifpPaperless06);

        $manager->flush();

        // Create a reference for each ifpIndexfichePaperless (to be used in other fixtures if needed)
        $this->addReference('ifp_indexfiche_paperless01', $ifpPaperless01);
        $this->addReference('ifp_indexfiche_paperless02', $ifpPaperless02);
        $this->addReference('ifp_indexfiche_paperless03', $ifpPaperless03);
        $this->addReference('ifp_indexfiche_paperless04', $ifpPaperless04);
        $this->addReference('ifp_indexfiche_paperless05', $ifpPaperless05);
        $this->addReference('ifp_indexfiche_paperless06', $ifpPaperless06);

        self::$ifp = array(
            $ifpPaperless01,
            $ifpPaperless02,
            $ifpPaperless03,
            $ifpPaperless04,
            $ifpPaperless05,
            $ifpPaperless06
        );
    }

    public function getOrder()
    {
        return 90;
    }

    /**
     * Ajout d'un document individuel gelé pour le salarié ALLAIGRE CARINE
     * @return IfpIndexfichePaperless
     */
    private function buildIfp01()
    {
        $ifpPaperless01 = new IfpIndexfichePaperless();
        $ifpPaperless01->setIfpDocumentsassocies('E000000000174/1/1');
        $ifpPaperless01->setIfpVdmLocalisation('S/C/1/179/440197');
        $ifpPaperless01->setIfpNombrepages(1);
        $ifpPaperless01->setIfpIdCodeChrono('');
        $ifpPaperless01->setIfpIdNumeroBoiteArchive('');
        $ifpPaperless01->setIfpInterbox(0);
        $ifpPaperless01->setIfpLotIndex('');
        $ifpPaperless01->setIfpLotProduction(0);
        $ifpPaperless01->setIfpIdNomSociete('POLYCLINIQUE ST FRANCOIS SAINT ANTOINE');
        $ifpPaperless01->setIfpIdNomClient('VITALIA CO');
        $ifpPaperless01->setIfpCodeClient('TSI504');
        $ifpPaperless01->setIfpIdCodeSociete('01');
        $ifpPaperless01->setIfpIdCodeEtablissement('01001');
        $ifpPaperless01->setIfpIdLibEtablissement('POLYCLINIQUE ST FRANCOIS SAINT ANTOINE');
        $ifpPaperless01->setIfpIdLibelleJalon('');
        $ifpPaperless01->setIfpIdNumSiren('917250151');
        $ifpPaperless01->setIfpIdNumSiret('91725015100029');
        $ifpPaperless01->setIfpIdIndiceClassement($this->getReference('typ_type01')->getTypCode());
        $ifpPaperless01->setIfpIdLibelleDocument('Justificatifs adresse');
        $ifpPaperless01->setIfpCodeDocument($this->getReference('typ_type01'));
        $ifpPaperless01->setIfpIdFormatDocument('PDFa');
        $ifpPaperless01->setIfpIdSourceDocument('BVRH UPLOAD');
        $ifpPaperless01->setIfpIdNumVersionDocument('1');
        $ifpPaperless01->setIfpIdPoidsDocument('000000149251');
        $ifpPaperless01->setIfpIdPeriodePaie('201510');
        $ifpPaperless01->setIfpIdDateArchivageDocument(new \DateTime('2015-10-29 17:46:14'));
        $ifpPaperless01->setIfpIdDureeArchivageDocument(30);
        $ifpPaperless01->setIfpIdDateFinArchivageDocument(new \DateTime('2045-10-29 17:46:14'));
        $ifpPaperless01->setIfpIdNomSalarie('ALLAIGRE');
        $ifpPaperless01->setIfpIdPrenomSalarie('CARINE');
        $ifpPaperless01->setIfpIdDateNaissanceSalarie(new \DateTime('1972-12-13 00:00:00'));
        $ifpPaperless01->setIfpIdDateEntree(new \DateTime('1999-09-02 00:00:00'));
        $ifpPaperless01->setIfpNumMatricule('01000002');
        $ifpPaperless01->setIfpIdNumMatriculeRh('01000002');
        $ifpPaperless01->setIfpIdNumMatriculeGroupe('');
        $ifpPaperless01->setIfpIdAnnotation('');
        $ifpPaperless01->setIfpIdConteneur('');
        $ifpPaperless01->setIfpIdBoite('');
        $ifpPaperless01->setIfpIdLot('');
        $ifpPaperless01->setIfpIdNumOrdre('1');
        $ifpPaperless01->setIfpArchiveCfec('1');
        $ifpPaperless01->setIfpArchiveSerialnumber('440197');
        $ifpPaperless01->setIfpArchiveDatetime('2015/10/29 17:46:20');
        $ifpPaperless01->setIfpArchiveName('upload20151029.pdf');
        $ifpPaperless01->setIfpArchiveCfe('179');
        $ifpPaperless01->setIfpNumeropdf('');
        $ifpPaperless01->setIfpOpnProvenance('INTERUPLOAD');
        $ifpPaperless01->setIfpStatusNum('OK');
        $ifpPaperless01->setIfpIsDoublon(false);
        $ifpPaperless01->setIfpLogin('expertms_DPS');
        $ifpPaperless01->setIfpModedt('');
        $ifpPaperless01->setIfpNumdtr('');
        $ifpPaperless01->setIfpIdCodeActivite('');
        $ifpPaperless01->setIfpCycleFinDeVie(false);
        $ifpPaperless01->setIfpGeler(true);
        $ifpPaperless01->setIfpSetFinArchivage(true);
        $ifpPaperless01->setIfpIsPersonnel(false);

        return $ifpPaperless01;
    }

    /**
     * Ajout d'un document individuel non gelé pour le salarié ACHENAIS STEPHANE
     * @return IfpIndexfichePaperless
     */
    private function buildIfp02()
    {
        $ifpPaperless02 = new IfpIndexfichePaperless();
        $ifpPaperless02->setIfpDocumentsassocies('E000000001207/1/1');
        $ifpPaperless02->setIfpVdmLocalisation('S/C/1/179/631321');
        $ifpPaperless02->setIfpNombrepages(1);
        $ifpPaperless02->setIfpIdCodeChrono('');
        $ifpPaperless02->setIfpIdNumeroBoiteArchive('');
        $ifpPaperless02->setIfpInterbox(0);
        $ifpPaperless02->setIfpLotIndex('');
        $ifpPaperless02->setIfpLotProduction(0);
        $ifpPaperless02->setIfpIdNomSociete('SOCIETE DEMO PARIS');
        $ifpPaperless02->setIfpIdNomClient('RSI CO');
        $ifpPaperless02->setIfpCodeClient('TSI504');
        $ifpPaperless02->setIfpIdCodeSociete('01');
        $ifpPaperless02->setIfpIdCodeEtablissement('00001');
        $ifpPaperless02->setIfpIdLibEtablissement('RSI TSI SIEGE PARIS');
        $ifpPaperless02->setIfpIdLibelleJalon('');
        $ifpPaperless02->setIfpIdNumSiren('393555123');
        $ifpPaperless02->setIfpIdNumSiret('39355512300057');
        $ifpPaperless02->setIfpIdIndiceClassement($this->getReference('typ_type02')->getTypCode());
        $ifpPaperless02->setIfpIdLibelleDocument('Changement état civil');
        $ifpPaperless02->setIfpCodeDocument($this->getReference('typ_type02'));
        $ifpPaperless02->setIfpIdFormatDocument('PDFa');
        $ifpPaperless02->setIfpIdSourceDocument('BVRH UPLOAD');
        $ifpPaperless02->setIfpIdNumVersionDocument('1');
        $ifpPaperless02->setIfpIdPoidsDocument('000000081403');
        $ifpPaperless02->setIfpIdPeriodePaie('201512');
        $ifpPaperless02->setIfpIdDateArchivageDocument(new \DateTime('2015-12-22 01:29:47'));
        $ifpPaperless02->setIfpIdDureeArchivageDocument(20);
        $ifpPaperless02->setIfpIdDateFinArchivageDocument(new \DateTime('2035-12-01 00:00:00'));
        $ifpPaperless02->setIfpIdNomSalarie('ACHENAIS');
        $ifpPaperless02->setIfpIdPrenomSalarie('STEPHANE');
        $ifpPaperless02->setIfpIdDateEntree(new \DateTime('1996-01-01 00:00:00'));
        $ifpPaperless02->setIfpIdDateSortie(new \DateTime('2014-02-28 00:00:00'));
        $ifpPaperless02->setIfpNumMatricule('01010234');
        $ifpPaperless02->setIfpIdNumMatriculeRh('01010234');
        $ifpPaperless02->setIfpIdNumMatriculeGroupe('');
        $ifpPaperless02->setIfpIdAnnotation('');
        $ifpPaperless02->setIfpIdConteneur('');
        $ifpPaperless02->setIfpIdBoite('');
        $ifpPaperless02->setIfpIdLot('');
        $ifpPaperless02->setIfpIdNumOrdre('1');
        $ifpPaperless02->setIfpArchiveCfec('1');
        $ifpPaperless02->setIfpArchiveSerialnumber('631321');
        $ifpPaperless02->setIfpArchiveDatetime('2015/12/22 01:29:50');
        $ifpPaperless02->setIfpArchiveName('upload20151222.pdf');
        $ifpPaperless02->setIfpArchiveCfe('179');
        $ifpPaperless02->setIfpNumeropdf('');
        $ifpPaperless02->setIfpOpnProvenance('INTERUPLOAD');
        $ifpPaperless02->setIfpStatusNum('OK');
        $ifpPaperless02->setIfpIsDoublon(false);
        $ifpPaperless02->setIfpLogin('expertms_DPS');
        $ifpPaperless02->setIfpModedt('');
        $ifpPaperless02->setIfpNumdtr('');
        $ifpPaperless02->setIfpIdCodeActivite('');
        $ifpPaperless02->setIfpCycleFinDeVie(false);
        $ifpPaperless02->setIfpGeler(false);
        $ifpPaperless02->setIfpSetFinArchivage(true);
        $ifpPaperless02->setIfpIsPersonnel(false);

        return $ifpPaperless02;
    }

    /**
     * Ajout d'un document individuel non gelé pour le salarié EUTEBERT EVELYNE
     * @return IfpIndexfichePaperless
     */
    private function buildIfp03()
    {
        $ifpPaperless03 = new IfpIndexfichePaperless();
        $ifpPaperless03->setIfpDocumentsassocies('E5017703000000/1/1');
        $ifpPaperless03->setIfpVdmLocalisation('S/C/1/179/394694');
        $ifpPaperless03->setIfpNombrepages(1);
        $ifpPaperless03->setIfpIdCodeChrono('');
        $ifpPaperless03->setIfpIdNumeroBoiteArchive('');
        $ifpPaperless03->setIfpInterbox(0);
        $ifpPaperless03->setIfpLotIndex('12560206_7903692_T44P3609');
        $ifpPaperless03->setIfpLotProduction(81);
        $ifpPaperless03->setIfpIdNomSociete('SOCIETE LILLE');
        $ifpPaperless03->setIfpIdNomClient('RSI CO');
        $ifpPaperless03->setIfpCodeClient('TSI504');
        $ifpPaperless03->setIfpIdCodeSociete('03');
        $ifpPaperless03->setIfpIdCodeEtablissement('00002');
        $ifpPaperless03->setIfpIdLibEtablissement('RSI TSI NORD LILLE LILLE');
        $ifpPaperless03->setIfpIdCodeJalon('JA');
        $ifpPaperless03->setIfpIdLibelleJalon('DESTINATAIRE N0 1');
        $ifpPaperless03->setIfpIdNumSiren('330740382');
        $ifpPaperless03->setIfpIdNumSiret('33074038200552');
        $ifpPaperless03->setIfpIdIndiceClassement($this->getReference('typ_type04')->getTypCode());
        $ifpPaperless03->setIfpIdLibelleDocument('BULLETIN PAIE 5');
        $ifpPaperless03->setIfpCodeDocument($this->getReference('typ_type04'));
        $ifpPaperless03->setIfpIdFormatDocument('PDF');
        $ifpPaperless03->setIfpIdSourceDocument('CEL');
        $ifpPaperless03->setIfpIdNumVersionDocument('1');
        $ifpPaperless03->setIfpIdPoidsDocument('000000057318');
        $ifpPaperless03->setIfpIdPeriodePaie('200910');
        $ifpPaperless03->setIfpIdDateArchivageDocument(new \DateTime('2015-09-01 20:53:12'));
        $ifpPaperless03->setIfpIdDureeArchivageDocument(5);
        $ifpPaperless03->setIfpIdDateFinArchivageDocument(new \DateTime('2020-09-01 20:53:12'));
        $ifpPaperless03->setIfpIdNomSalarie('EUTEBERT');
        $ifpPaperless03->setIfpIdPrenomSalarie('EVELYNE');
        $ifpPaperless03->setIfpIdNomJeuneFilleSalarie('DUPONT');
        $ifpPaperless03->setIfpIdDateEntree(new \DateTime('1996-01-01 00:00:00'));
        $ifpPaperless03->setIfpIdDateNaissanceSalarie(new \DateTime('1972-12-13 00:00:00'));
        $ifpPaperless03->setIfpNumMatricule('01000352');
        $ifpPaperless03->setIfpIdNumMatriculeRh('01000352');
        $ifpPaperless03->setIfpIdNumMatriculeGroupe('');
        $ifpPaperless03->setIfpIdAnnotation('');
        $ifpPaperless03->setIfpIdConteneur('');
        $ifpPaperless03->setIfpIdBoite('');
        $ifpPaperless03->setIfpIdLot('');
        $ifpPaperless03->setIfpIdNumOrdre('0');
        $ifpPaperless03->setIfpArchiveCfec('1');
        $ifpPaperless03->setIfpArchiveSerialnumber('394694');
        $ifpPaperless03->setIfpArchiveDatetime('2015-09-01T20:50:16+02:00');
        $ifpPaperless03->setIfpArchiveName('00000824.pdf');
        $ifpPaperless03->setIfpArchiveCfe('179');
        $ifpPaperless03->setIfpNumeropdf('1');
        $ifpPaperless03->setIfpOpnProvenance('');
        $ifpPaperless03->setIfpStatusNum('OK');
        $ifpPaperless03->setIfpIsDoublon(false);
        $ifpPaperless03->setIfpLogin('expertms_DPS');
        $ifpPaperless03->setIfpModedt('');
        $ifpPaperless03->setIfpNumdtr('085');
        $ifpPaperless03->setIfpIdCodeActivite('');
        $ifpPaperless03->setIfpCycleFinDeVie(false);
        $ifpPaperless03->setIfpGeler(false);
        $ifpPaperless03->setIfpSetFinArchivage(true);
        $ifpPaperless03->setIfpIsPersonnel(false);
        $ifpPaperless03->setIfpIdAlphanum1('ALPHA 01');

        return $ifpPaperless03;
    }

    /**
     * Ajout d'un document collectif gelé
     * @return IfpIndexfichePaperless
     */
    private function buildIfp04()
    {
        $ifpPaperless04 = new IfpIndexfichePaperless();
        $ifpPaperless04->setIfpDocumentsassocies('E1000000000/1/4');
        $ifpPaperless04->setIfpVdmLocalisation('S/C/1/179/321457');
        $ifpPaperless04->setIfpNombrepages(4);
        $ifpPaperless04->setIfpIdCodeChrono('');
        $ifpPaperless04->setIfpIdNumeroBoiteArchive('');
        $ifpPaperless04->setIfpInterbox(0);
        $ifpPaperless04->setIfpLotIndex('9926937_5660785_ADPMVSS_SDISPLAA_G0417202');
        $ifpPaperless04->setIfpLotProduction(2);
        $ifpPaperless04->setIfpIdNomSociete('SOCIETE DEMO PARIS');
        $ifpPaperless04->setIfpIdNomClient('RSI CO');
        $ifpPaperless04->setIfpCodeClient('TSI504');
        $ifpPaperless04->setIfpIdCodeSociete('01');
        $ifpPaperless04->setIfpIdCodeEtablissement('00001');
        $ifpPaperless04->setIfpIdLibEtablissement('RSI TSI SIEGE PARIS');
        $ifpPaperless04->setIfpIdCodeJalon('JA');
        $ifpPaperless04->setIfpIdLibelleJalon('');
        $ifpPaperless04->setIfpIdNumSiren('393555123');
        $ifpPaperless04->setIfpIdNumSiret('39355512300057');
        $ifpPaperless04->setIfpIdIndiceClassement($this->getReference('typ_type03')->getTypCode());
        $ifpPaperless04->setIfpIdLibelleDocument('RECAP. RUBRIQUES S');
        $ifpPaperless04->setIfpCodeDocument($this->getReference('typ_type03'));
        $ifpPaperless04->setIfpIdFormatDocument('PDF');
        $ifpPaperless04->setIfpIdSourceDocument('SPOOL_ADP');
        $ifpPaperless04->setIfpIdNumVersionDocument('1');
        $ifpPaperless04->setIfpIdPoidsDocument('000000021158');
        $ifpPaperless04->setIfpIdPeriodeExerciceSociale('2014');
        $ifpPaperless04->setIfpIdPeriodePaie('201403');
        $ifpPaperless04->setIfpIdDateArchivageDocument(new \DateTime('2014-07-11 10:42:38'));
        $ifpPaperless04->setIfpIdDureeArchivageDocument(30);
        $ifpPaperless04->setIfpIdNumMatriculeRh('');
        $ifpPaperless04->setIfpIdNumMatriculeGroupe('');
        $ifpPaperless04->setIfpIdAnnotation('');
        $ifpPaperless04->setIfpIdConteneur('');
        $ifpPaperless04->setIfpIdBoite('');
        $ifpPaperless04->setIfpIdLot('');
        $ifpPaperless04->setIfpIdNumOrdre('1');
        $ifpPaperless04->setIfpArchiveCfec('1');
        $ifpPaperless04->setIfpArchiveSerialnumber('321457');
        $ifpPaperless04->setIfpArchiveDatetime('2014-07-11T10:43:19+02:00');
        $ifpPaperless04->setIfpArchiveName('1000000000.pdf');
        $ifpPaperless04->setIfpArchiveCfe('179');
        $ifpPaperless04->setIfpNumeropdf('');
        $ifpPaperless04->setIfpOpnProvenance('INTERUPLOAD');
        $ifpPaperless04->setIfpStatusNum('OK');
        $ifpPaperless04->setIfpIsDoublon(false);
        $ifpPaperless04->setIfpLogin('');
        $ifpPaperless04->setIfpModedt('ME0085');
        $ifpPaperless04->setIfpNumdtr('089');
        $ifpPaperless04->setIfpIdCodeActivite('');
        $ifpPaperless04->setIfpCycleFinDeVie(false);
        $ifpPaperless04->setIfpGeler(true);
        $ifpPaperless04->setIfpSetFinArchivage(true);
        $ifpPaperless04->setIfpIsPersonnel(false);

        return $ifpPaperless04;
    }

    /**
     * Ajout d'un document collectif non gelé
     * @return IfpIndexfichePaperless
     */
    private function buildIfp05()
    {
        $ifpPaperless05 = new IfpIndexfichePaperless();
        $ifpPaperless05->setIfpDocumentsassocies('E1000000009/1/1');
        $ifpPaperless05->setIfpVdmLocalisation('S/C/1/179/362066');
        $ifpPaperless05->setIfpNombrepages(1);
        $ifpPaperless05->setIfpIdCodeChrono('');
        $ifpPaperless05->setIfpIdNumeroBoiteArchive('');
        $ifpPaperless05->setIfpInterbox(0);
        $ifpPaperless05->setIfpLotIndex('11971728_7402134_ADPMVSS_SDISPLAA_E1812132');
        $ifpPaperless05->setIfpLotProduction(53);
        $ifpPaperless05->setIfpIdNomSociete('SOCIETE DEMO LYON');
        $ifpPaperless05->setIfpIdNomClient('RSI CO');
        $ifpPaperless05->setIfpCodeClient('TSI504');
        $ifpPaperless05->setIfpIdCodeSociete('02');
        $ifpPaperless05->setIfpIdCodeEtablissement('00003');
        $ifpPaperless05->setIfpIdLibEtablissement('RSI TSI RHONE LYON');
        $ifpPaperless05->setIfpIdCodeJalon('JC');
        $ifpPaperless05->setIfpIdLibelleJalon('DESTINATAIRE N0 3');
        $ifpPaperless05->setIfpIdNumSiren('330740382');
        $ifpPaperless05->setIfpIdNumSiret('33074038280448');
        $ifpPaperless05->setIfpIdIndiceClassement($this->getReference('typ_type03')->getTypCode());
        $ifpPaperless05->setIfpIdLibelleDocument('JUSTIF. DECL.PERIOD.');
        $ifpPaperless05->setIfpCodeDocument($this->getReference('typ_type03'));
        $ifpPaperless05->setIfpIdFormatDocument('PDF');
        $ifpPaperless05->setIfpIdSourceDocument('BVRH UPLOAD');
        $ifpPaperless05->setIfpIdNumVersionDocument('1');
        $ifpPaperless05->setIfpIdPoidsDocument('000000013121');
        $ifpPaperless05->setIfpIdPeriodePaie('201502');
        $ifpPaperless05->setIfpIdDateArchivageDocument(new \DateTime('2015-06-29 15:22:35'));
        $ifpPaperless05->setIfpIdDureeArchivageDocument(30);
        $ifpPaperless05->setIfpIdLibre1('DSN3');
        $ifpPaperless05->setIfpIdLibre1Date('W238 : DSN3');
        $ifpPaperless05->setIfpIdNumMatriculeRh('');
        $ifpPaperless05->setIfpIdNumMatriculeGroupe('');
        $ifpPaperless05->setIfpIdAnnotation('');
        $ifpPaperless05->setIfpIdConteneur('');
        $ifpPaperless05->setIfpIdBoite('');
        $ifpPaperless05->setIfpIdLot('');
        $ifpPaperless05->setIfpIdNumOrdre('1');
        $ifpPaperless05->setIfpArchiveCfec('1');
        $ifpPaperless05->setIfpArchiveSerialnumber('362072');
        $ifpPaperless05->setIfpArchiveDatetime('2015-06-29T15:22:50+02:00');
        $ifpPaperless05->setIfpArchiveName('1000000008.pdf');
        $ifpPaperless05->setIfpArchiveCfe('179');
        $ifpPaperless05->setIfpNumeropdf('');
        $ifpPaperless05->setIfpOpnProvenance('');
        $ifpPaperless05->setIfpStatusNum('OK');
        $ifpPaperless05->setIfpIsDoublon(false);
        $ifpPaperless05->setIfpLogin('MyUsrLogin01');
        $ifpPaperless05->setIfpModedt('ME0260');
        $ifpPaperless05->setIfpNumdtr('089');
        $ifpPaperless05->setIfpIdCodeActivite('');
        $ifpPaperless05->setIfpCycleFinDeVie(false);
        $ifpPaperless05->setIfpGeler(false);
        $ifpPaperless05->setIfpSetFinArchivage(true);
        $ifpPaperless05->setIfpIsPersonnel(false);

        return $ifpPaperless05;
    }

    /**
     * Ajout d'un document collectif non gelé
     * @return IfpIndexfichePaperless
     */
    private function buildIfp06()
    {
        $ifpPaperless06 = new IfpIndexfichePaperless();
        $ifpPaperless06->setIfpDocumentsassocies('E2000000000/1/5');
        $ifpPaperless06->setIfpVdmLocalisation('S/C/1/179/321459');
        $ifpPaperless06->setIfpNombrepages(4);
        $ifpPaperless06->setIfpIdCodeChrono('');
        $ifpPaperless06->setIfpIdNumeroBoiteArchive('');
        $ifpPaperless06->setIfpInterbox(0);
        $ifpPaperless06->setIfpLotIndex('9926937_5660785_ADPMVSS_SDISPLAA_G0418303');
        $ifpPaperless06->setIfpLotProduction(2);
        $ifpPaperless06->setIfpIdNomSociete('SOCIETE DEMO TOULOUSE');
        $ifpPaperless06->setIfpIdNomClient('RSI CO');
        $ifpPaperless06->setIfpCodeClient('TSI504');
        $ifpPaperless06->setIfpIdCodeSociete('01');
        $ifpPaperless06->setIfpIdCodeEtablissement('00001');
        $ifpPaperless06->setIfpIdLibEtablissement('RSI TSI SIEGE PARIS');
        $ifpPaperless06->setIfpIdCodeJalon('JA');
        $ifpPaperless06->setIfpIdLibelleJalon('');
        $ifpPaperless06->setIfpIdNumSiren('393555129');
        $ifpPaperless06->setIfpIdNumSiret('39355512300059');
        $ifpPaperless06->setIfpIdIndiceClassement($this->getReference('typ_type03')->getTypCode());
        $ifpPaperless06->setIfpIdLibelleDocument('RECAP. RUBRIQUES Z');
        $ifpPaperless06->setIfpCodeDocument($this->getReference('typ_type03'));
        $ifpPaperless06->setIfpIdFormatDocument('PDF');
        $ifpPaperless06->setIfpIdSourceDocument('CEL');
        $ifpPaperless06->setIfpIdNumVersionDocument('1');
        $ifpPaperless06->setIfpIdPoidsDocument('000000021158');
        $ifpPaperless06->setIfpIdPeriodePaie('201503');
        $ifpPaperless06->setIfpIdDateArchivageDocument(new \DateTime('2015-07-11 10:42:38'));
        $ifpPaperless06->setIfpIdDureeArchivageDocument(30);
        $ifpPaperless06->setIfpIdNumMatriculeRh('');
        $ifpPaperless06->setIfpIdNumMatriculeGroupe('');
        $ifpPaperless06->setIfpIdAnnotation('');
        $ifpPaperless06->setIfpIdConteneur('');
        $ifpPaperless06->setIfpIdBoite('');
        $ifpPaperless06->setIfpIdLot('');
        $ifpPaperless06->setIfpIdNumOrdre('1');
        $ifpPaperless06->setIfpArchiveCfec('1');
        $ifpPaperless06->setIfpArchiveSerialnumber('321459');
        $ifpPaperless06->setIfpArchiveDatetime('2015-07-11T10:43:19+02:00');
        $ifpPaperless06->setIfpArchiveName('1000000001.pdf');
        $ifpPaperless06->setIfpArchiveCfe('179');
        $ifpPaperless06->setIfpNumeropdf('');
        $ifpPaperless06->setIfpOpnProvenance('INTERUPLOAD');
        $ifpPaperless06->setIfpStatusNum('OK');
        $ifpPaperless06->setIfpIsDoublon(false);
        $ifpPaperless06->setIfpLogin('MyUsrLogin02');
        $ifpPaperless06->setIfpModedt('ME0085');
        $ifpPaperless06->setIfpNumdtr('089');
        $ifpPaperless06->setIfpIdCodeActivite('');
        $ifpPaperless06->setIfpCycleFinDeVie(false);
        $ifpPaperless06->setIfpGeler(false);
        $ifpPaperless06->setIfpSetFinArchivage(true);
        $ifpPaperless06->setIfpIsPersonnel(false);
        $ifpPaperless06->setIfpIdAlphanum1('ALPHA 01');

        return $ifpPaperless06;
    }
}
