<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\IhaImportHabilitation;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIhaImportHabilitationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $habilitationImport = new IhaImportHabilitation();
        $habilitationImport->setIhaTraite(5);
        $habilitationImport->setIhaSucces(5);
        $habilitationImport->setIhaErreur(0);
        $habilitationImport->setIhaRapport($this->getReference('rap_rapport01'));
        $manager->persist($habilitationImport);

        $habilitationImport = new IhaImportHabilitation();
        $habilitationImport->setIhaTraite(3);
        $habilitationImport->setIhaSucces(2);
        $habilitationImport->setIhaErreur(1);
        $habilitationImport->setIhaRapport($this->getReference('rap_rapport02'));
        $manager->persist($habilitationImport);

        $manager->flush();
    }

    public function getOrder()
    {
        return 200;
    }
}
