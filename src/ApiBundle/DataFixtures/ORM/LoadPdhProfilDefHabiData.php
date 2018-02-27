<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 22/03/2016
 * Time: 16:25
 */

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Enum\EnumLabelModeHabilitationType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPdhProfilDefHabiData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $filtre1 = new PdhProfilDefHabi();
        $filtre1->setPdhLibelle('Saisie predictive par nom');
        $filtre1->setPdhMode(EnumLabelModeHabilitationType::MIXED_MODE);
        $filtre1->setPdhHabilitationI(
            '<operator>
                <type>and</type>
                <operand>
                    <comparator>
                        <field>ID_CODE_CLIENT</field>
                        <type>eq</type>
                        <value>TSI504</value>
                    </comparator>
                </operand>
            </operator>'
        );
        $filtre1->setPdhHabilitationC(
            '<operator>
                <type>and</type>
                <operand>
                    <comparator>
                        <field>ID_CODE_CLIENT</field>
                        <type>eq</type>
                        <value>TSI504</value>
                    </comparator>
                </operand>
            </operator>'
        );
        $manager->persist($filtre1);
        $this->addReference('pdh_profil_def_habi01', $filtre1);

        $filtre2 = new PdhProfilDefHabi();
        $filtre2->setPdhLibelle('Saisie predictive SIRET');
        $filtre2->setPdhHabilitationI(
            '<operator>
                <type>and</type>
                <operand>
                    <comparator>
                        <field>ID_NUM_SIRET</field>
                        <type>between</type>
                        <value>39355512300057</value>
                        <value>33074038280448</value>
                    </comparator>
                </operand>
            </operator>'
        );
        $filtre2->setPdhHabilitationC('');
        $manager->persist($filtre2);
        $this->addReference('pdh_profil_def_habi02', $filtre2);

        $manager->flush();

    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 120;
    }
}
