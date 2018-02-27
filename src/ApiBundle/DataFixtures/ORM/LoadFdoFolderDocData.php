<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\FdoFolderDoc;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFdoFolderDocData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $folderDoc01 = new FdoFolderDoc();
        $folderDoc01->setFdoDoc($this->getReference('ifp_indexfiche_paperless01'));
        $folderDoc01->setFdoFolder($this->getReference('fol_folder01'));
        $manager->persist($folderDoc01);

        $folderDoc02 = new FdoFolderDoc();
        $folderDoc02->setFdoDoc($this->getReference('ifp_indexfiche_paperless02'));
        $folderDoc02->setFdoFolder($this->getReference('fol_folder01'));
        $manager->persist($folderDoc02);

        $folderDoc03 = new FdoFolderDoc();
        $folderDoc03->setFdoDoc($this->getReference('ifp_indexfiche_paperless03'));
        $folderDoc03->setFdoFolder($this->getReference('fol_folder01'));
        $manager->persist($folderDoc03);

        $folderDoc04 = new FdoFolderDoc();
        $folderDoc04->setFdoDoc($this->getReference('ifp_indexfiche_paperless04'));
        $folderDoc04->setFdoFolder($this->getReference('fol_folder01'));
        $manager->persist($folderDoc04);

        $folderDoc05 = new FdoFolderDoc();
        $folderDoc05->setFdoDoc($this->getReference('ifp_indexfiche_paperless01'));
        $folderDoc05->setFdoFolder($this->getReference('bas_basket01'));
        $manager->persist($folderDoc05);

        $folderDoc06 = new FdoFolderDoc();
        $folderDoc06->setFdoDoc($this->getReference('ifp_indexfiche_paperless02'));
        $folderDoc06->setFdoFolder($this->getReference('bas_basket01'));
        $manager->persist($folderDoc06);

        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }
}