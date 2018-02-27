<?php
namespace ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ApiBundle\Entity\ConConfig;
use Doctrine\Common\Persistence\ObjectManager;

class LoadConConfigData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $config = new ConConfig();
        $config->setConVariable('pac');
        $config->setConValeur('TSI504');
        $config->setConLabel('Code pac');
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('type_abo_visu');
        $config->setConValeur(2);
        $config->setConLabel('Type abonnement visu');
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('right_annotation_doc');
        $config->setConLabel('Annotation des documents');
        $config->setConValeur(7);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('right_annotation_dossier');
        $config->setConLabel('Annotation des dossiers');
        $config->setConValeur(7);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('right_recherche_doc');
        $config->setConLabel('Droits sur les documents');
        $config->setConValeur(7);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('access_statistiques');
        $config->setConLabel('Accès aux statistiques');
        $config->setConValeur(1);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('access_export_pdf_excel');
        $config->setConLabel('Export PDF vers Excel');
        $config->setConValeur(1);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('access_import_masse');
        $config->setConLabel('Import de masse');
        $config->setConValeur(1);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('access_boite_archive');
        $config->setConLabel('Accès à la boîte d\'archive');
        $config->setConValeur(1);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('export_synchrone_documents_limit');
        $config->setConLabel('Limite de l\'export Excel');
        $config->setConValeur(3);
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('version');
        $config->setConLabel('Version du BVRH');
        $config->setConValeur('BVRH 5.0');
        $manager->persist($config);

        $config = new ConConfig();
        $config->setConVariable('multi_pac');
        $config->setConLabel('Instance multipac ?');
        $config->setConValeur('N');
        $manager->persist($config);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 130;
    }
}
