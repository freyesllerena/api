<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\ComCompletude;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadComCompletudeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout d'une complétude pour MyUsrLogin01
         */
        $completude01 = new ComCompletude();
        $completude01->setComUser($this->getReference('usr_users01'));
        $completude01->setComLibelle('La complétude de test de MyUsrLogin01');
        $completude01->setComPrivee(true);
        $completude01->setComAuto(false);
        $completude01->setComPeriode('quotidien');
        $completude01->setComAvecDocuments(true);
        $completude01->setComPopulation('ALL_POP');
        $completude01->setComNotification('EnAttente');
        $manager->persist($completude01);

        /**
         * Ajout d'une complétude pour MyUsrLogin02
         */
        $completude02 = new ComCompletude();
        $completude02->setComUser($this->getReference('usr_users02'));
        $completude02->setComLibelle('La complétude de test de MyUsrLogin02');
        $completude02->setComPrivee(true);
        $completude02->setComAuto(false);
        $completude02->setComPeriode('hebdomadaire');
        $completude02->setComAvecDocuments(false);
        $completude02->setComPopulation('PRE_POP');
        $completude02->setComNotification('EnAttente');
        $manager->persist($completude02);

        /**
         * Ajout d'une complétude pour MyUsrLogin02
         */
        $completude03 = new ComCompletude();
        $completude03->setComUser($this->getReference('usr_users02'));
        $completude03->setComLibelle('La deuxième complétude de test de MyUsrLogin02');
        $completude03->setComPrivee(false);
        $completude03->setComAuto(true);
        $completude03->setComEmail('alexandre@symfony.com;thomas@symfony.com;fabien@symfony.com');
        $completude03->setComPeriode('mensuel');
        $completude03->setComAvecDocuments(false);
        $completude03->setComPopulation('OUT_POP');
        $completude03->setComNotification('EnAttente');
        $manager->persist($completude03);

        $manager->flush();

        // Create a reference for each ComCompletude (to be used in other fixtures if needed)
        $this->addReference('com_completude01', $completude01);
        $this->addReference('com_completude02', $completude02);
        $this->addReference('com_completude03', $completude03);
    }

    public function getOrder()
    {
        return 160;
    }
}
