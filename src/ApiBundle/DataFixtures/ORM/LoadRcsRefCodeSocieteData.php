<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\RcsRefCodeSociete;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRcsRefCodeSocieteData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Codes Sociétés
         */

        // Code Société 900000001
        $rcsRef01 = new RcsRefCodeSociete();
        $rcsRef01->setRcsCode('000001');
        $rcsRef01->setRcsIdCodeClient('TSI504');
        $rcsRef01->setRcsLibelle('Société Test 01');
        $rcsRef01->setRcsSiren('900000001');

        $manager->persist($rcsRef01);

        // Code Société 900000002
        $rcsRef02 = new RcsRefCodeSociete();
        $rcsRef02->setRcsCode('000009');
        $rcsRef02->setRcsIdCodeClient('TSI504');
        $rcsRef02->setRcsLibelle('Société Test 09');
        $rcsRef02->setRcsSiren('900000009');

        $manager->persist($rcsRef02);

        // Code Société 900000003
        $rcsRef03 = new RcsRefCodeSociete();
        $rcsRef03->setRcsCode('000002');
        $rcsRef03->setRcsIdCodeClient('TSI504');
        $rcsRef03->setRcsLibelle('Société Test 02');
        $rcsRef03->setRcsSiren('900000002');

        $manager->persist($rcsRef03);

        $manager->flush();

        $this->addReference('rcs_ref01', $rcsRef01);
        $this->addReference('rcs_ref02', $rcsRef02);
        $this->addReference('rcs_ref03', $rcsRef03);
    }

    public function getOrder()
    {
        return 10;
    }
}
