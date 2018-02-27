<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\CtyCompletudeType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCtyCompletudeTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout du tiroir D150010 dans la completude 1
         */
        $completudeType = new CtyCompletudeType();
        $completudeType->setCtyCompletude($this->getReference('com_completude01'));
        $completudeType->setCtyType($this->getReference('typ_type01'));
        $manager->persist($completudeType);

        /**
         * Ajout du tiroir D256900 dans la complétude 1
         */
        $completudeType = new CtyCompletudeType();
        $completudeType->setCtyCompletude($this->getReference('com_completude01'));
        $completudeType->setCtyType($this->getReference('typ_type02'));
        $manager->persist($completudeType);

        /**
         * Ajout du tiroir D465200 dans la complétude 2
         */
        $completudeType = new CtyCompletudeType();
        $completudeType->setCtyCompletude($this->getReference('com_completude02'));
        $completudeType->setCtyType($this->getReference('typ_type04'));
        $manager->persist($completudeType);

        /**
         * Ajout du tiroir D150010 dans la completude 3
         */
        $completudeType = new CtyCompletudeType();
        $completudeType->setCtyCompletude($this->getReference('com_completude03'));
        $completudeType->setCtyType($this->getReference('typ_type01'));
        $manager->persist($completudeType);

        $manager->flush();
    }

    public function getOrder()
    {
        return 170;
    }
}
