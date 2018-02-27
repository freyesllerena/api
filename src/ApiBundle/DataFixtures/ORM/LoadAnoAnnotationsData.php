<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\AnoAnnotations;
use ApiBundle\Enum\EnumLabelEtatType;
use ApiBundle\Enum\EnumLabelStatutType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAnoAnnotationsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Ajout d'une annotation de MyUsrLogin01 sur Doc 1 en privée
         */
        $annotation = new AnoAnnotations();
        $annotation->setAnoFiche($this->getReference('ifp_indexfiche_paperless01'));
        $annotation->setAnoLogin($this->getReference('usr_users01'));
        $annotation->setAnoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotation->setAnoStatut(EnumLabelStatutType::PRIVATE_STATUT);
        $annotation->setAnoTexte('myAnnotation - 1');
        $manager->persist($annotation);

        /**
         * Ajout d'une annotation de MyUsrLogin02 sur Doc 1 en public
         */
        $annotation = new AnoAnnotations();
        $annotation->setAnoFiche($this->getReference('ifp_indexfiche_paperless01'));
        $annotation->setAnoLogin($this->getReference('usr_users02'));
        $annotation->setAnoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotation->setAnoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotation->setAnoTexte('myAnnotation - 2');
        $manager->persist($annotation);

        /**
         * Ajout d'une annotation de MyUsrLogin02 sur Doc 1 en privée
         */
        $annotation = new AnoAnnotations();
        $annotation->setAnoFiche($this->getReference('ifp_indexfiche_paperless01'));
        $annotation->setAnoLogin($this->getReference('usr_users02'));
        $annotation->setAnoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotation->setAnoStatut(EnumLabelStatutType::PRIVATE_STATUT);
        $annotation->setAnoTexte('myAnnotation - 3');
        $manager->persist($annotation);

        /**
         * Ajout d'une annotation de MyUsrLogin02 sur Doc 3 en public
         */
        $annotation = new AnoAnnotations();
        $annotation->setAnoFiche($this->getReference('ifp_indexfiche_paperless03'));
        $annotation->setAnoLogin($this->getReference('usr_users02'));
        $annotation->setAnoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotation->setAnoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotation->setAnoTexte('myAnnotation - 4');
        $manager->persist($annotation);

        /**
         * Ajout d'une annotation de MyUsrLogin01 sur Doc 1 en public mais à l'état supprimé
         */
        $annotation = new AnoAnnotations();
        $annotation->setAnoFiche($this->getReference('ifp_indexfiche_paperless01'));
        $annotation->setAnoLogin($this->getReference('usr_users01'));
        $annotation->setAnoEtat(EnumLabelEtatType::DELETE_ETAT);
        $annotation->setAnoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotation->setAnoTexte('myAnnotation - 5');
        $manager->persist($annotation);

        /**
         * Ajout d'une annotation de MyUsrLogin01 sur Doc 1 en public
         */
        $annotation = new AnoAnnotations();
        $annotation->setAnoFiche($this->getReference('ifp_indexfiche_paperless01'));
        $annotation->setAnoLogin($this->getReference('usr_users01'));
        $annotation->setAnoEtat(EnumLabelEtatType::ACTIVE_ETAT);
        $annotation->setAnoStatut(EnumLabelStatutType::PUBLIC_STATUT);
        $annotation->setAnoTexte('myAnnotation - 6');
        $manager->persist($annotation);

        $manager->flush();
    }

    public function getOrder()
    {
        return 140;
    }
}