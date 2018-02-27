<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\FusFolderUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFusFolderUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $folderUser01 = new FusFolderUser();
        $folderUser01->setFusFolder($this->getReference('fol_folder01'));
        $folderUser01->setFusUser($this->getReference('usr_users01'));
        $manager->persist($folderUser01);

        $folderUser02 = new FusFolderUser();
        $folderUser02->setFusFolder($this->getReference('fol_folder01'));
        $folderUser02->setFusUser($this->getReference('usr_users02'));
        $manager->persist($folderUser02);
        $manager->flush();
    }

    public function getOrder()
    {
        return 80;
    }
}