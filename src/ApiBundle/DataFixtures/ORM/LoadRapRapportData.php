<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\RapRapport;
use ApiBundle\Enum\EnumLabelEtatReportType;
use ApiBundle\Enum\EnumLabelTypeRapportType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRapRapportData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout d'un rapport d'import en masse des habilitations OK
         */
        $report01 = new RapRapport();
        $report01->setRapTypeRapport(EnumLabelTypeRapportType::HABILITATION_TYPE);
        $report01->setRapFichier('');
        $report01->setRapLibelleFic('report_habilitation.csv');
        $report01->setRapEtat(EnumLabelEtatReportType::OK_ETAT_REPORT);
        $manager->persist($report01);

        /**
         * Ajout d'un rapport d'import en masse des habilitations KO
         */
        $report02 = new RapRapport();
        $report02->setRapTypeRapport(EnumLabelTypeRapportType::HABILITATION_TYPE);
        $report02->setRapFichier('');
        $report02->setRapLibelleFic('report_habilitation.csv');
        $report02->setRapEtat(EnumLabelEtatReportType::KO_ETAT_REPORT);
        $manager->persist($report02);

        /**
         * Ajout d'un rapport d'import de masse
         */
        $report03 = new RapRapport();
        $report03->setRapTypeRapport(EnumLabelTypeRapportType::IMPORT_TYPE);
        $report03->setRapFichier(
            'a:2:{i:0;a:1:{i:0;s:13:"Flux rejeté.";}i:1;a:1:{i:0;s:40:"Les pages suivantes sont incorrectes : 1";}}'
        );
        $report03->setRapLibelleFic('rejected.csv');
        $report03->setRapEtat(EnumLabelEtatReportType::KO_ETAT_REPORT);
        $manager->persist($report03);

        $manager->flush();

        $this->addReference('rap_rapport01', $report01);
        $this->addReference('rap_rapport02', $report02);
    }

    public function getOrder()
    {
        return 190;
    }
}
