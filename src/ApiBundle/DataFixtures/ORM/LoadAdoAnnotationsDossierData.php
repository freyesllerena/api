<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\AdoAnnotationsDossier;
use ApiBundle\Enum\EnumLabelEtatType;
use ApiBundle\Enum\EnumLabelStatutType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAdoAnnotationsDossierData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout d'une annotation de MyUsrLogin01 sur Dossier 1 en privée
         */
        $annotationDossier = new AdoAnnotationsDossier();
        $annotationDossier->setAdoFolder($this->getReference('fol_folder01'));
        $annotationDossier->setAdoLogin($this->getReference('usr_users01'));
        $annotationDossier->setAdoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotationDossier->setAdoStatut(EnumLabelStatutType::PRIVATE_STATUT);
        $annotationDossier->setAdoTexte('myAnnotation dossier - 1');
        $manager->persist($annotationDossier);

        /**
         * Ajout d'une annotation de MyUsrLogin02 sur Dossier 1 en public
         */
        $annotationDossier = new AdoAnnotationsDossier();
        $annotationDossier->setAdoFolder($this->getReference('fol_folder01'));
        $annotationDossier->setAdoLogin($this->getReference('usr_users02'));
        $annotationDossier->setAdoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotationDossier->setAdoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotationDossier->setAdoTexte('myAnnotation dossier - 2');
        $manager->persist($annotationDossier);

        /**
         * Ajout d'une annotation de MyUsrLogin02 sur Dossier 1 en privée
         */
        $annotationDossier = new AdoAnnotationsDossier();
        $annotationDossier->setAdoFolder($this->getReference('fol_folder01'));
        $annotationDossier->setAdoLogin($this->getReference('usr_users02'));
        $annotationDossier->setAdoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotationDossier->setAdoStatut(EnumLabelStatutType::PRIVATE_STATUT);
        $annotationDossier->setAdoTexte('myAnnotation dossier - 3');
        $manager->persist($annotationDossier);

        /**
         * Ajout d'une annotation de MyUsrLogin02 sur Dossier 2 en public
         */
        $annotationDossier = new AdoAnnotationsDossier();
        $annotationDossier->setAdoFolder($this->getReference('fol_folder02'));
        $annotationDossier->setAdoLogin($this->getReference('usr_users02'));
        $annotationDossier->setAdoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotationDossier->setAdoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotationDossier->setAdoTexte('myAnnotation dossier - 4');
        $manager->persist($annotationDossier);

        /**
         * Ajout d'une annotation de MyUsrLogin01 sur Dossier 1 en public mais à l'état supprimé
         */
        $annotationDossier = new AdoAnnotationsDossier();
        $annotationDossier->setAdoFolder($this->getReference('fol_folder01'));
        $annotationDossier->setAdoLogin($this->getReference('usr_users01'));
        $annotationDossier->setAdoEtat(EnumLabelEtatType::DELETE_ETAT);
        $annotationDossier->setAdoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotationDossier->setAdoTexte('myAnnotation dossier - 5');
        $manager->persist($annotationDossier);

        /**
         * Ajout d'une annotation de MyUsrLogin01 sur Dossier 1 en public
         */
        $annotationDossier = new AdoAnnotationsDossier();
        $annotationDossier->setAdoFolder($this->getReference('fol_folder01'));
        $annotationDossier->setAdoLogin($this->getReference('usr_users01'));
        $annotationDossier->setAdoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotationDossier->setAdoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotationDossier->setAdoTexte('myAnnotation dossier - 6');
        $manager->persist($annotationDossier);

        $manager->flush();
    }

    public function getOrder()
    {
        return 150;
    }
}