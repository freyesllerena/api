<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\UprUserPreferences;
use ApiBundle\Entity\UsrUsers;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUprUserPreferencesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $pref01 = new UprUserPreferences();
        $pref01->setUprUser($this->getReference('usr_users02'));
        $pref01->setUprDevice('DES');
        $pref01->setUprType('dashboard');
        $pref01->setUprData('some data');
        $manager->persist($pref01);

        $pref02 = new UprUserPreferences();
        $pref02->setUprUser($this->getReference('usr_users02'));
        $pref02->setUprDevice('DES');
        $pref02->setUprType('plandeclassement');
        $pref02->setUprData('some others data');
        $manager->persist($pref02);

        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }
}
