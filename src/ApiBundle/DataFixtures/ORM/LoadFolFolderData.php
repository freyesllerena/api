<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\BasBasket;
use ApiBundle\Entity\FolFolder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFolFolderData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $folder01 = new FolFolder();
        $folder01->setFolLibelle('Mon Dossier Test');
        $folder01->setFolIdOwner('MyUsrLogin01');
        $folder01->setFolNbDoc(5);
        $manager->persist($folder01);

        $folder02 = new FolFolder();
        $folder02->setFolLibelle('Attestations Test');
        $folder02->setFolIdOwner('MyUsrLogin02');
        $folder02->setFolNbDoc(10);
        $manager->persist($folder02);

        $manager->flush();

        $this->addReference('fol_folder01', $folder01);
        $this->addReference('fol_folder02', $folder02);

        $basket01 = new BasBasket();
        $basket01->setFolLibelle('Panier utilisateur "MyUsrLogin01"');
        $basket01->setFolIdOwner('MyUsrLogin01');
        $basket01->setFolNbDoc(5);
        $manager->persist($basket01);

        $basket02 = new BasBasket();
        $basket02->setFolLibelle('Panier utilisateur "MyUsrLogin02"');
        $basket02->setFolIdOwner('MyUsrLogin02');
        $basket02->setFolNbDoc(10);
        $manager->persist($basket02);

        $manager->flush();

        $this->addReference('bas_basket01', $basket01);
        $this->addReference('bas_basket02', $basket02);
    }

    public function getOrder()
    {
        return 30;
    }
}