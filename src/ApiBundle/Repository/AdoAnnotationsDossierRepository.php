<?php

namespace ApiBundle\Repository;

use ApiBundle\Enum\EnumLabelEtatType;
use ApiBundle\Enum\EnumLabelStatutType;
use Doctrine\ORM\EntityRepository;

/**
 * AdoAnnotationsDossierRepository
 *
 * Class AdoAnnotationsDossierRepository
 * @package ApiBundle\Repository
 */
class AdoAnnotationsDossierRepository extends EntityRepository
{
    /**
     * Liste les annotations d'un dossier
     *
     * @param int $folderId L'id du dossier
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return array
     */
    public function findAnnotationsFolderList($folderId, $userLogin)
    {
        return $this->_em->createQuery(
            "SELECT
              ado.adoId,
              ado.adoStatut,
              ado.adoTexte,
              ado.adoCreatedAt,
              usr.usrLogin,
              usr.usrNom,
              usr.usrPrenom
            FROM 'ApiBundle\Entity\AdoAnnotationsDossier' ado
            LEFT JOIN 'ApiBundle\Entity\UsrUsers' usr
             WITH ado.adoLogin = usr.usrLogin
            WHERE ado.adoFolder = :folder
              AND ado.adoEtat = '" . EnumLabelEtatType::ACTIVE_ETAT . "'
              AND (ado.adoStatut = '" . EnumLabelStatutType::PUBLIC_STATUT . "'
               OR ado.adoStatut = '" . EnumLabelStatutType::PRIVATE_STATUT . "'
                AND ado.adoLogin = :login)
            ORDER BY ado.adoId DESC"
        )
            ->setParameters(['folder' => $folderId, 'login' => $userLogin])
            ->getArrayResult();
    }
}
