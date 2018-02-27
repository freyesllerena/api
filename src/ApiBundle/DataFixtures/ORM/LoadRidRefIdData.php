<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\RidRefId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRidRefIdData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Codes PAC
         */

        // Code PAC TSI504
        $refId01 = new RidRefId();
        $refId01->setRidIdCodeClient('TSI504');
        $refId01->setRidType('CodeClient');
        $refId01->setRidCode('TSI504');
        $refId01->setRidLibelle('Libellé PAC TSI504');

        $manager->persist($refId01);

        // Code PAC TSI071
        $refId02 = new RidRefId();
        $refId02->setRidIdCodeClient('TSI071');
        $refId02->setRidType('CodeClient');
        $refId02->setRidCode('TSI071');
        $refId02->setRidLibelle('Libellé PAC TSI071');

        $manager->persist($refId02);

        // Code PAC TSI501
        $refId03 = new RidRefId();
        $refId03->setRidIdCodeClient('TSI501');
        $refId03->setRidType('CodeClient');
        $refId03->setRidCode('TSI501');
        $refId03->setRidLibelle('Libellé PAC TSI501');

        $manager->persist($refId03);

        /**
         * Types de contrats
         */

        // Contrat emploi jeunes
        $refId04 = new RidRefId();
        $refId04->setRidIdCodeClient('TSI504');
        $refId04->setRidType('TypeContrat');
        $refId04->setRidCode('06');
        $refId04->setRidLibelle('CONTRAT EMPLOI JEUNES');

        $manager->persist($refId04);

        /**
         * Types de Jalon
         */

        // Contrat emploi jeunes
        $refId05 = new RidRefId();
        $refId05->setRidIdCodeClient('TSI504');
        $refId05->setRidType('IdCodeJalon');
        $refId05->setRidCode('JA');
        $refId05->setRidLibelle('DESTINATAIRE N0 1');

        $manager->persist($refId05);

        /**
         * Types de code catégorie professionnelle
         */
        $refId06 = new RidRefId();
        $refId06->setRidIdCodeClient('TSI504')
            ->setRidType('IdCodeCategProfessionnelle')
            ->setRidCode('05')
            ->setRidLibelle('PDG');

        $manager->persist($refId06);

        /**
         * Types affectation 1
         */
        $refId07 = new RidRefId();
        $refId07->setRidIdCodeClient('TSI504')
            ->setRidType('IdAffectation1')
            ->setRidCode('01')
            ->setRidLibelle('DIRECTION GENERALE');

        $manager->persist($refId07);


        /**
         * Types affectation 2
         */
        $refId08 = new RidRefId();
        $refId08->setRidIdCodeClient('TSI504')
            ->setRidType('IdAffectation2')
            ->setRidCode('0001')
            ->setRidLibelle('DIRECTION');

        $manager->persist($refId08);

        /**
         * Types affectation 3
         */
        $refId09 = new RidRefId();
        $refId09->setRidIdCodeClient('TSI504')
            ->setRidType('IdAffectation3')
            ->setRidCode('01')
            ->setRidLibelle('EUROPE PARIS');

        $manager->persist($refId09);

        /**
         * Types libre1
         */
        $refId10 = new RidRefId();
        $refId10->setRidIdCodeClient('TSI504')
            ->setRidType('IdLibre1')
            ->setRidCode('01')
            ->setRidLibelle('LOREM IPSUM');

        $manager->persist($refId10);

        /**
         * Types libre1
         */
        $refId11 = new RidRefId();
        $refId11->setRidIdCodeClient('TSI504')
            ->setRidType('IdLibre2')
            ->setRidCode('01')
            ->setRidLibelle('DOLOR SIT AMET');

        $manager->persist($refId11);

        /**
         * Type Code activité
         */
        $refId12 = new RidRefId();
        $refId12->setRidIdCodeClient('TSI504')
            ->setRidType('IdCodeActivite')
            ->setRidCode('01')
            ->setRidLibelle('CONSECTETUR ADSICIPING');

        $manager->persist($refId12);

        $manager->flush();

        $this->addReference('rid_refid01', $refId01);
        $this->addReference('rid_refid02', $refId02);
        $this->addReference('rid_refid03', $refId03);
        $this->addReference('rid_refid04', $refId04);
        $this->addReference('rid_refid05', $refId05);
        $this->addReference('rid_refid06', $refId06);
        $this->addReference('rid_refid07', $refId07);
        $this->addReference('rid_refid08', $refId08);
        $this->addReference('rid_refid09', $refId09);
        $this->addReference('rid_refid10', $refId10);
        $this->addReference('rid_refid11', $refId11);
    }

    public function getOrder()
    {
        return 10;
    }
}

