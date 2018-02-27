<?php

namespace ApiBundle\Repository;

use ApiBundle\Enum\EnumLabelEtatType;
use ApiBundle\Enum\EnumLabelStatutType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * AnoAnnotationsRepository
 *
 * Class AnoAnnotationsRepository
 * @package ApiBundle\Repository
 */
class AnoAnnotationsRepository extends BaseRepository
{
    public function __construct(EntityManager $entityManager, ClassMetadata $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * Liste les annotations d'un document
     *
     * @param int $documentId L'id du document
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return array
     */
    public function findAnnotationsList($documentId, $userLogin)
    {
        return $this->_em->createQuery(
            "SELECT 
              ano.anoId,
              ano.anoStatut,
              ano.anoTexte,
              ano.anoCreatedAt,
              usr.usrLogin,
              usr.usrNom,
              usr.usrPrenom
            FROM 'ApiBundle\Entity\AnoAnnotations' ano
            LEFT JOIN 'ApiBundle\Entity\UsrUsers' usr
             WITH ano.anoLogin = usr.usrLogin
            WHERE ano.anoFiche = :document
              AND ano.anoEtat = '" . EnumLabelEtatType::ACTIVE_ETAT . "'
              AND (ano.anoStatut = '" . EnumLabelStatutType::PUBLIC_STATUT . "'
               OR ano.anoStatut = '" . EnumLabelStatutType::PRIVATE_STATUT . "'
                AND ano.anoLogin = :login)
            ORDER BY ano.anoId DESC"
        )
            ->setParameters(['document' => $documentId, 'login' => $userLogin])
            ->getArrayResult();
    }
}
