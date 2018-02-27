<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\IinIdxIndiv;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIinIdxIndivData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout du salarié ALLAIGRE CARINE
         */
        $iinIdxIndiv01 = new IinIdxIndiv();
        $iinIdxIndiv01->setIinCodeClient("TSI504");
        $iinIdxIndiv01->setIinIdCodeSociete("01");
        $iinIdxIndiv01->setIinIdCodeEtablissement("00001");
        $iinIdxIndiv01->setIinIdLibEtablissement("POLYCLINIQUE ST FRANCOIS SAINT ANTOINE");
        $iinIdxIndiv01->setIinIdNomSociete("POLYCLINIQUE ST FRANCOIS SAINT ANTOINE");
        $iinIdxIndiv01->setIinIdNomClient("VITALIA CO");
        $iinIdxIndiv01->setIinIdPeriodePaie("201506");
        $iinIdxIndiv01->setIinIdNomSalarie("ALLAIGRE");
        $iinIdxIndiv01->setIinIdPrenomSalarie("CARINE");
        $iinIdxIndiv01->setIinIdNomJeuneFilleSalarie("");
        $iinIdxIndiv01->setIinIdDateEntree(new \DateTime("1999-09-02 00:00:00"));
        $iinIdxIndiv01->setIinIdNumNir("272120318505692");
        $iinIdxIndiv01->setIinNumMatricule("01000002");
        $iinIdxIndiv01->setIinIdNumMatriculeRh("01000002");
        $iinIdxIndiv01->setIinFichierIndex("ADPLINS_PAPINDIV_F3015082");
        $iinIdxIndiv01->setIinIdCodeCategProfessionnelle("30");
        $iinIdxIndiv01->setIinIdCodeCategSocioProf("30");
        $iinIdxIndiv01->setIinIdTypeContrat("00");
        $iinIdxIndiv01->setIinIdAffectation1("01");
        $iinIdxIndiv01->setIinIdAffectation2("SOIN");
        $iinIdxIndiv01->setIinIdAffectation3("PMD");
        $iinIdxIndiv01->setIinIdNumSiren("917250151");
        $iinIdxIndiv01->setIinIdNumSiret("91725015100029");
        $iinIdxIndiv01->setIinIdDateNaissanceSalarie(new \DateTime("1972-12-13 00:00:00"));
        $iinIdxIndiv01->setIinIdCodeActivite("01");
        $iinIdxIndiv01->setIinCreatedAt(new \DateTime("2016-03-11 20:04:00"));
        $iinIdxIndiv01->setIinUpdatedAt(new \DateTime("2016-03-11 20:04:00"));

        $iinIdxIndiv01->setIinIdTypePaie("");
        $iinIdxIndiv01->setIinIdCodeJalon("");
        $iinIdxIndiv01->setIinIdLibre1("");
        $iinIdxIndiv01->setIinIdLibre2("");
        $iinIdxIndiv01->setIinIdNumMatriculeGroupe("");

        $manager->persist($iinIdxIndiv01);

        /**
         * Ajout du salarié ACHENAIS STEPHANE
         */
        $iinIdxIndiv02 = new IinIdxIndiv();
        $iinIdxIndiv02->setIinCodeClient("TSI504");
        $iinIdxIndiv02->setIinIdCodeSociete("01");
        $iinIdxIndiv02->setIinIdCodeEtablissement("00001");
        $iinIdxIndiv02->setIinIdLibEtablissement("RSI TSI SIEGE PARIS");
        $iinIdxIndiv02->setIinIdNomSociete("SOCIETE DEMO PARIS");
        $iinIdxIndiv02->setIinIdNomClient("RSI CO");
        $iinIdxIndiv02->setIinIdPeriodePaie("201511");
        $iinIdxIndiv02->setIinIdNomSalarie("ACHENAIS");
        $iinIdxIndiv02->setIinIdPrenomSalarie("STEPHANE");
        $iinIdxIndiv02->setIinIdDateEntree(new \DateTime("1996-01-01 00:00:00"));
        $iinIdxIndiv02->setIinIdDateSortie(new \DateTime("2014-02-28 00:00:00"));
        $iinIdxIndiv02->setIinIdNumNir("1541054382179");
        $iinIdxIndiv02->setIinNumMatricule("01010234");
        $iinIdxIndiv02->setIinIdNumMatriculeRh("01010234");
        $iinIdxIndiv02->setIinFichierIndex("ADPMVSS_PAPINDIV_L0111001");
        $iinIdxIndiv02->setIinIdCodeCategProfessionnelle("13");
        $iinIdxIndiv02->setIinIdCodeCategSocioProf("221");
        $iinIdxIndiv02->setIinIdTypeContrat("01");
        $iinIdxIndiv02->setIinIdAffectation1("BVRH AFF1");
        $iinIdxIndiv02->setIinIdAffectation2("BVRH AFF2");
        $iinIdxIndiv02->setIinIdAffectation3("BVRH AFF3");
        $iinIdxIndiv02->setIinIdNumSiren("393555123");
        $iinIdxIndiv02->setIinIdNumSiret("39355512300057");
        $iinIdxIndiv02->setIinIdDateNaissanceSalarie(new \DateTime("1954-10-07 00:00:00"));
        $iinIdxIndiv02->setIinIdCodeActivite("02");
        $iinIdxIndiv02->setIinIdNumOrdre("0");
        $iinIdxIndiv02->setIinCreatedAt(new \DateTime("2016-03-11 20:04:00"));
        $iinIdxIndiv02->setIinUpdatedAt(new \DateTime("2016-03-11 20:04:00"));

        $iinIdxIndiv02->setIinIdTypePaie("");
        $iinIdxIndiv02->setIinIdCodeJalon("");
        $iinIdxIndiv02->setIinIdLibre1("");
        $iinIdxIndiv02->setIinIdLibre2("");
        $iinIdxIndiv02->setIinIdNumMatriculeGroupe("");

        $manager->persist($iinIdxIndiv02);

        /**
         * Ajout du salarié EUTEBERT EVELYNE
         */
        $iinIdxIndiv03 = new IinIdxIndiv();
        $iinIdxIndiv03->setIinCodeClient("TSI504");
        $iinIdxIndiv03->setIinIdCodeSociete("03");
        $iinIdxIndiv03->setIinIdCodeEtablissement("00002");
        $iinIdxIndiv03->setIinIdLibEtablissement("RSI TSI NORD LILLE LILLE");
        $iinIdxIndiv03->setIinIdNomSociete("SOCIETE LILLE");
        $iinIdxIndiv03->setIinIdNomClient("RSI CO");
        $iinIdxIndiv03->setIinIdPeriodePaie("201511");
        $iinIdxIndiv03->setIinIdNomSalarie("EUTEBERT");
        $iinIdxIndiv03->setIinIdPrenomSalarie("EVELYNE");
        $iinIdxIndiv03->setIinIdNomJeuneFilleSalarie("");
        $iinIdxIndiv03->setIinIdDateEntree(new \DateTime("1996-01-01 00:00:00"));
        $iinIdxIndiv03->setIinIdNumNir("2520145190286");
        $iinIdxIndiv03->setIinNumMatricule("01000352");
        $iinIdxIndiv03->setIinIdNumMatriculeRh("01000352");
        $iinIdxIndiv03->setIinFichierIndex("ADPMVSS_PAPINDIV_L0111001");
        $iinIdxIndiv03->setIinIdCodeCategProfessionnelle("30");
        $iinIdxIndiv03->setIinIdCodeCategSocioProf("243");
        $iinIdxIndiv03->setIinIdTypeContrat("01");
        $iinIdxIndiv03->setIinIdAffectation1("BVRH AFF1");
        $iinIdxIndiv03->setIinIdAffectation2("BVRH AFF2");
        $iinIdxIndiv03->setIinIdAffectation3("BVRH AFF3");
        $iinIdxIndiv03->setIinIdNumSiren("330740382");
        $iinIdxIndiv03->setIinIdNumSiret("33074038200552");
        $iinIdxIndiv03->setIinIdDateNaissanceSalarie(new \DateTime("1952-01-03 00:00:00"));
        $iinIdxIndiv03->setIinIdCodeActivite("02");
        $iinIdxIndiv03->setIinIdNumOrdre("0");
        $iinIdxIndiv03->setIinCreatedAt(new \DateTime("2016-03-11 20:04:00"));
        $iinIdxIndiv03->setIinUpdatedAt(new \DateTime("2016-03-11 20:04:00"));

        $iinIdxIndiv03->setIinIdTypePaie("");
        $iinIdxIndiv03->setIinIdCodeJalon("");
        $iinIdxIndiv03->setIinIdLibre1("");
        $iinIdxIndiv03->setIinIdLibre2("");
        $iinIdxIndiv03->setIinIdNumMatriculeGroupe("");

        $manager->persist($iinIdxIndiv03);
        $manager->flush();

        /**
         * Ajout du salarié MARTIN PHILIPPE
         */
        $iinIdxIndiv04 = new IinIdxIndiv();
        $iinIdxIndiv04->setIinCodeClient("TSI504");
        $iinIdxIndiv04->setIinIdCodeSociete("03");
        $iinIdxIndiv04->setIinIdCodeEtablissement("00003");
        $iinIdxIndiv04->setIinIdLibEtablissement("RSI TSI RHONE LYON");
        $iinIdxIndiv04->setIinIdNomSociete("SOCIETE DEMO LYON");
        $iinIdxIndiv04->setIinIdNomClient("RSI CO");
        $iinIdxIndiv04->setIinIdPeriodePaie("201511");
        $iinIdxIndiv04->setIinIdNomSalarie("MARTIN");
        $iinIdxIndiv04->setIinIdPrenomSalarie("PHILIPPE");
        $iinIdxIndiv04->setIinIdNomJeuneFilleSalarie("");
        $iinIdxIndiv04->setIinIdDateEntree(new \DateTime("1996-01-01 00:00:00"));
        $iinIdxIndiv04->setIinIdNumNir("2520145190286");
        $iinIdxIndiv04->setIinNumMatricule("01000123");
        $iinIdxIndiv04->setIinIdNumMatriculeRh("01000123");
        $iinIdxIndiv04->setIinFichierIndex("ADPMVSS_PAPINDIV_L0111001");
        $iinIdxIndiv04->setIinIdCodeCategProfessionnelle("30");
        $iinIdxIndiv04->setIinIdCodeCategSocioProf("243");
        $iinIdxIndiv04->setIinIdTypeContrat("01");
        $iinIdxIndiv04->setIinIdAffectation1("BVRH AFF1");
        $iinIdxIndiv04->setIinIdAffectation2("BVRH AFF2");
        $iinIdxIndiv04->setIinIdAffectation3("BVRH AFF3");
        $iinIdxIndiv04->setIinIdNumSiren("330740399");
        $iinIdxIndiv04->setIinIdNumSiret("33074039900552");
        $iinIdxIndiv04->setIinIdDateNaissanceSalarie(new \DateTime("1952-01-03 00:00:00"));
        $iinIdxIndiv04->setIinIdCodeActivite("02");
        $iinIdxIndiv04->setIinIdNumOrdre("0");
        $iinIdxIndiv04->setIinCreatedAt(new \DateTime("2016-03-11 20:04:00"));
        $iinIdxIndiv04->setIinUpdatedAt(new \DateTime("2016-03-11 20:04:00"));

        $iinIdxIndiv04->setIinIdTypePaie("");
        $iinIdxIndiv04->setIinIdCodeJalon("");
        $iinIdxIndiv04->setIinIdLibre1("");
        $iinIdxIndiv04->setIinIdLibre2("");
        $iinIdxIndiv04->setIinIdNumMatriculeGroupe("");

        $manager->persist($iinIdxIndiv04);
        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
