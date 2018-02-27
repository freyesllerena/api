<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 22/03/2016
 * Time: 16:25
 */

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\PdeProfilDef;
use ApiBundle\Entity\PusProfilUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPusProfilUserData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userProfil01 = new PusProfilUser();
        $userProfil01->setPusProfil($this->getReference('pro_profil01'));
        $userProfil01->setPusUser($this->getReference('usr_users01'));
        $manager->persist($userProfil01);

        $userProfil02 = new PusProfilUser();
        $userProfil02->setPusProfil($this->getReference('pro_profil02'));
        $userProfil02->setPusUser($this->getReference('usr_users02'));
        $manager->persist($userProfil02);

        $userProfil03 = new PusProfilUser();
        $userProfil03->setPusProfil($this->getReference('pro_profil03'));
        $userProfil03->setPusUser($this->getReference('usr_users03'));
        $manager->persist($userProfil03);

        $manager->flush();

    }

    public function getOrder()
    {
        return 70;
    }
}
