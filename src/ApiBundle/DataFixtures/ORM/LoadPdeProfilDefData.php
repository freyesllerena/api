<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\PdeProfilDef;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPdeProfilDefData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $profilDef = new PdeProfilDef();
        $profilDef->setPdeId($this->getReference('pro_profil01')->getProId());
        $profilDef->setPdeIdProfilDef($this->getReference('pda_profil_def_appli01')->getPdaId());
        $profilDef->setPdeType('appli');
        $manager->persist($profilDef);

        $profilDef = new PdeProfilDef();
        $profilDef->setPdeId($this->getReference('pro_profil01')->getProId());
        $profilDef->setPdeIdProfilDef($this->getReference('pdh_profil_def_habi01')->getPdhId());
        $profilDef->setPdeType('habi');
        $manager->persist($profilDef);

        $profilDef = new PdeProfilDef();
        $profilDef->setPdeId($this->getReference('pro_profil02')->getProId());
        $profilDef->setPdeIdProfilDef($this->getReference('pda_profil_def_appli02')->getPdaId());
        $profilDef->setPdeType('appli');
        $manager->persist($profilDef);

        $profilDef = new PdeProfilDef();
        $profilDef->setPdeId($this->getReference('pro_profil02')->getProId());
        $profilDef->setPdeIdProfilDef($this->getReference('pdh_profil_def_habi01')->getPdhId());
        $profilDef->setPdeType('habi');
        $manager->persist($profilDef);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 130;
    }
}
