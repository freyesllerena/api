<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\DicDictionnaire;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDicDictionnaireData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Traductions du plan de classement
        $this->setTranslationClassificationPlan($manager);
        // Traductions des thématiques et processus
        $this->setTranslationProcess($manager);
        // Traduction des langues
        $this->setTranslationLang($manager);
        // Traduction des champs de ifp_indexfiche_paperless
        $this->setTranslationIfpField($manager);
        // Traduction des en-têtes des rapports en masse des habilitations
        $this->setTranslationHabilitationReport($manager);
        // Flush
        $manager->flush();
    }

    public function getOrder()
    {
        return 40;
    }

    /**
     * @param ObjectManager $manager
     */
    private function setTranslationClassificationPlan(ObjectManager $manager)
    {
        // Ajout de la traduction du domaine 2 pour la langue fr_FR et le support Desktop
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcNiv1-2');
        $dictionary->setDicValeur('Test niveau 1: 2');
        $manager->persist($dictionary);
        $manager->flush();

        // Ajout de la traduction du domaine 2 pour la langue en_EN et le support Desktop
        $dictionary->setDicValeur('Test level 1: 2');
        $dictionary->setTranslatableLocale('en_EN');
        $manager->persist($dictionary);
        $manager->flush();

        // Ajout de la traduction du domaine 2 pour la langue fr_FR et le support Mobile
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('MOB');
        $dictionary->setDicCode('pdcNiv1-2');
        $dictionary->setDicValeur('Test niveau 1: 2');
        $manager->persist($dictionary);
        $manager->flush();

        // Ajout de la traduction du domaine 2 pour la langue en_EN et le support Mobile
        $dictionary->setDicValeur('Test level 1: 2');
        $dictionary->setTranslatableLocale('en_EN');
        $manager->persist($dictionary);
        $manager->flush();

        // Ajout de la traduction du sous-domaine 10 pour le support Desktop
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcNiv2-210');
        $dictionary->setDicValeur('Test niveau 2: 210');
        $manager->persist($dictionary);

        // Ajout de la traduction du sous-domaine 10 pour le support Mobile
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('MOB');
        $dictionary->setDicCode('pdcNiv2-210');
        $dictionary->setDicValeur('Test niveau 2: 210');
        $manager->persist($dictionary);

        // Ajout de la traduction du sous sous-domaine 25
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcNiv3-21025');
        $dictionary->setDicValeur('Test niveau 3: 21025');
        $manager->persist($dictionary);

        // Ajout de la traduction du tiroir D150010
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcTiroir-D150010');
        $dictionary->setDicValeur('Test tiroir D150010');
        $manager->persist($dictionary);

        // Ajout de la traduction du tiroir D256900
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcTiroir-D256900');
        $dictionary->setDicValeur('Test tiroir D256900');
        $manager->persist($dictionary);

        // Ajout de la traduction du tiroir D325800
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcTiroir-D325800');
        $dictionary->setDicValeur('Test tiroir D325800');
        $manager->persist($dictionary);

        // Ajout de la traduction du domaine 3
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcNiv1-3');
        $dictionary->setDicValeur('Test niveau 1: 3');
        $manager->persist($dictionary);

        // Ajout de la traduction du tiroir D465200
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('pdcTiroir-D465200');
        $dictionary->setDicValeur('Test tiroir D465200');
        $manager->persist($dictionary);
    }

    /**
     * @param ObjectManager $manager
     */
    private function setTranslationProcess(ObjectManager $manager)
    {
        // Ajout de la traduction de la thématique 2
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('processusGroupe2');
        $dictionary->setDicValeur('Gestion des arrêts de travail');
        $manager->persist($dictionary);

        // Ajout de la traduction de la thématique 5
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('processusGroupe5');
        $dictionary->setDicValeur('Bénéfices');
        $manager->persist($dictionary);

        // Ajout de la traduction de la thématique 8
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('processusGroupe8');
        $dictionary->setDicValeur('GTA, feuille de présence');
        $manager->persist($dictionary);

        // Ajout de la traduction de la thématique 12
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('processusGroupe12');
        $dictionary->setDicValeur('Accords entreprise, OTT');
        $manager->persist($dictionary);
    }

    /**
     * @param ObjectManager $manager
     */
    private function setTranslationLang(ObjectManager $manager)
    {
        // Ajout de la langue fr_FR pour le support Desktop
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('language_fr_FR');
        $dictionary->setDicValeur('Français');
        $manager->persist($dictionary);

        // Ajout de la langue en_EN pour le support Desktop
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('language_en_EN');
        $dictionary->setDicValeur('English');
        $manager->persist($dictionary);

        // Ajout de la langue it_IT pour le support Desktop
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('language_it_IT');
        $dictionary->setDicValeur('Italiano');
        $manager->persist($dictionary);
    }

    /**
     * @param ObjectManager $manager
     */
    private function setTranslationIfpField(ObjectManager $manager)
    {
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdCodeSociete');
        $dictionary->setDicValeur('Code société');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdCodeEtablissement');
        $dictionary->setDicValeur('Code établissement');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdNumSiret');
        $dictionary->setDicValeur('N° Siret');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdLibelleDocument');
        $dictionary->setDicValeur('Libellé du document');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdNomSalarie');
        $dictionary->setDicValeur('Nom du salarié');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdPrenomSalarie');
        $dictionary->setDicValeur('Prénom du salarié');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('ifpIdDateNaissanceSalarie');
        $dictionary->setDicValeur('Date de naissance du salarié');
        $manager->persist($dictionary);
    }

    /**
     * @param ObjectManager $manager
     */
    private function setTranslationHabilitationReport(ObjectManager $manager)
    {
        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportLogin');
        $dictionary->setDicValeur('Login');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportSurname');
        $dictionary->setDicValeur('Nom');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportFirstname');
        $dictionary->setDicValeur('Prénom');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportPopulationFilter');
        $dictionary->setDicValeur('Filtre sur population');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportApplicativeFilter');
        $dictionary->setDicValeur('Filtre applicatif');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportRightDocSearch');
        $dictionary->setDicValeur('Droit Recherche de doc.');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportRightOrder');
        $dictionary->setDicValeur('Droit Classer');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportRightLifeCycle');
        $dictionary->setDicValeur('Droit Cycle de vie');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportRightManageUsers');
        $dictionary->setDicValeur('Droit Gestion des utilisateurs');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportRightDocAnnotation');
        $dictionary->setDicValeur('Droit Annotation des documents');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportRightFolderAnnotation');
        $dictionary->setDicValeur('Droit Annotation des dossiers');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportAccessCELExport');
        $dictionary->setDicValeur('Accès Export CEL');
        $manager->persist($dictionary);

        $dictionary = new DicDictionnaire();
        $dictionary->setDicSupport('DES');
        $dictionary->setDicCode('headerUsrUsersHabilitationExportAccessUnitImport');
        $dictionary->setDicValeur('Accès Import unitaire');
        $manager->persist($dictionary);
    }
}
