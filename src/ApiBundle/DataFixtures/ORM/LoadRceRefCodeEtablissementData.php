<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\RceRefCodeEtablissement;
use ApiBundle\Entity\RcsRefCodeSociete;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRceRefCodeEtablissementData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Codes Sociétés
         */

        // Code Société 900000001
        $obj1 = new RceRefCodeEtablissement();
        $obj1->setRceCode('01')
            ->setRceLibelle('Etablissement 01')
            ->setRceSociete($this->getReference('rcs_ref01'))
            ->setRceNic('1');

        $manager->persist($obj1);

        // Code Société 900000002
        $obj2 = new RceRefCodeEtablissement();
        $obj2->setRceCode('02')
            ->setRceLibelle('Etablissement 02')
            ->setRceSociete($this->getReference('rcs_ref02'))
            ->setRceNic('1');

        $manager->persist($obj2);

        $manager->flush();

        $this->addReference('rce_ref01', $obj1);
        $this->addReference('rce_ref02', $obj2);
    }

    public function getOrder()
    {
        return 100;
    }
}
