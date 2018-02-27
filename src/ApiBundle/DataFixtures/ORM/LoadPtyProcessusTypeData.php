<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\PtyProcessusType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPtyProcessusTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout du tiroir D150010 dans le processus 1
         */
        $processType = new PtyProcessusType();
        $processType->setPtyProcessus($this->getReference('pro_processus01'));
        $processType->setPtyType($this->getReference('typ_type01'));
        $manager->persist($processType);

        /**
         * Ajout du tiroir D325800 dans le processus 1
         */
        $processType = new PtyProcessusType();
        $processType->setPtyProcessus($this->getReference('pro_processus01'));
        $processType->setPtyType($this->getReference('typ_type03'));
        $manager->persist($processType);

        /**
         * Ajout du tiroir D325800 dans le processus 2
         */
        $processType = new PtyProcessusType();
        $processType->setPtyProcessus($this->getReference('pro_processus02'));
        $processType->setPtyType($this->getReference('typ_type03'));
        $manager->persist($processType);

        /**
         * Ajout du tiroir D465200 dans le processus 2
         */
        $processType = new PtyProcessusType();
        $processType->setPtyProcessus($this->getReference('pro_processus02'));
        $processType->setPtyType($this->getReference('typ_type04'));
        $manager->persist($processType);

        $manager->flush();
    }

    public function getOrder()
    {
        return 190;
    }
}
