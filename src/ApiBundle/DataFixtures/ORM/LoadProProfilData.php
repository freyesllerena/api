<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 22/03/2016
 * Time: 16:25
 */

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\PdeProfilDef;
use ApiBundle\Entity\ProProfil;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadProProfilData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $profil01 = new ProProfil();
        $profil01->setProLibelle("MyUsrLogin01_2014-12-12 12:04:33");
        $manager->persist($profil01);
        $this->addReference('pro_profil01', $profil01);

        $profil02 = new ProProfil();
        $profil02->setProLibelle("MyUsrLogin02_2014-12-12 12:04:33");
        $manager->persist($profil02);
        $this->addReference('pro_profil02', $profil02);

        $profil03 = new ProProfil();
        $profil03->setProLibelle("MyUsrLogin03_2014-12-12 12:04:33");
        $manager->persist($profil03);
        $this->addReference('pro_profil03', $profil03);

        $manager->flush();
    }

    public function getOrder()
    {
        return 60;
    }
}
