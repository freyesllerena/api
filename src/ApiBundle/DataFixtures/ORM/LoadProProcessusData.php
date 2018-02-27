<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\ProProcessus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProProcessusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout d'un processus perso. pour MyUsrLogin01 dans la thématique 2
         */
        $process01 = new ProProcessus();
        $process01->setProGroupe(2);
        $process01->setProLibelle('Processus perso. de MyUsrLogin01');
        $process01->setProUser($this->getReference('usr_users01'));
        $manager->persist($process01);

        /**
         * Ajout d'un processus perso. pour MyUsrLogin02 dans la thématique 5
         */
        $process02 = new ProProcessus();
        $process02->setProGroupe(5);
        $process02->setProLibelle('Processus perso. de MyUsrLogin02');
        $process02->setProUser($this->getReference('usr_users02'));
        $manager->persist($process02);

        $manager->flush();

        // Create a reference for each ProProcessus (to be used in other fixtures if needed)
        $this->addReference('pro_processus01', $process01);
        $this->addReference('pro_processus02', $process02);
    }

    public function getOrder()
    {
        return 180;
    }
}
