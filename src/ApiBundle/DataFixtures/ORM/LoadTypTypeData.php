<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\TypType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTypTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout du tiroir D150010 dans le domaine 2
         */
        $type01 = new TypType();
        $type01->setTypCode('D150010');
        $type01->setTypCodeAdp('MyTypCodeAdp');
        $type01->setTypIdNiveau(2);
        $type01->setTypType(2);
        $type01->setTypIndividuel(true);
        $type01->setTypVieDuree(30);
        $type01->setTypNumOrdre1(1);
        $type01->setTypNumOrdre2(2);
        $type01->setTypNumOrdre3(3);
        $type01->setTypNumOrdre4(4);
        $type01->setTypDateEffet('MyTypDateEffet');
        $manager->persist($type01);

        /**
         * Ajout du tiroir D256900 dans le domaine 2, sous-domaine 10
         */
        $type02 = new TypType();
        $type02->setTypCode('D256900');
        $type02->setTypCodeAdp('MyTypCodeAdp');
        $type02->setTypIdNiveau(210);
        $type02->setTypType(2);
        $type02->setTypIndividuel(true);
        $type02->setTypVieDuree(30);
        $type02->setTypNumOrdre1(1);
        $type02->setTypNumOrdre2(2);
        $type02->setTypNumOrdre3(3);
        $type02->setTypNumOrdre4(4);
        $type02->setTypDateEffet('MyTypDateEffet');
        $manager->persist($type02);

        /**
         * Ajout du tiroir D325800 dans le domaine 2, sous-domaine 10, sous sous-domaine 25
         */
        $type03 = new TypType();
        $type03->setTypCode('D325800');
        $type03->setTypCodeAdp('MyTypCodeAdp');
        $type03->setTypIdNiveau(21025);
        $type03->setTypType(2);
        $type03->setTypIndividuel(false);
        $type03->setTypVieDuree(30);
        $type03->setTypNumOrdre1(1);
        $type03->setTypNumOrdre2(2);
        $type03->setTypNumOrdre3(3);
        $type03->setTypNumOrdre4(4);
        $type03->setTypDateEffet('MyTypDateEffet');
        $manager->persist($type03);

        /**
         * Ajout du tiroir D465200 dans le domaine 3
         */
        $type04 = new TypType();
        $type04->setTypCode('D465200');
        $type04->setTypCodeAdp('MyTypCodeAdp');
        $type04->setTypIdNiveau(3);
        $type04->setTypType(2);
        $type04->setTypIndividuel(true);
        $type04->setTypVieDuree(30);
        $type04->setTypNumOrdre1(1);
        $type04->setTypNumOrdre2(2);
        $type04->setTypNumOrdre3(3);
        $type04->setTypNumOrdre4(4);
        $type04->setTypDateEffet('MyTypDateEffet');
        $manager->persist($type04);

        $manager->flush();

        $this->addReference('typ_type01', $type01);
        $this->addReference('typ_type02', $type02);
        $this->addReference('typ_type03', $type03);
        $this->addReference('typ_type04', $type04);
    }

    /**
     * Retourne le numéro d'ordre du chargement des fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }
}
