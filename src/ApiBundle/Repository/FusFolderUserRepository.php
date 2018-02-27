<?php

namespace ApiBundle\Repository;

use ApiBundle\Enum\EnumLabelUserType;
use Doctrine\ORM\EntityRepository;

class FusFolderUserRepository extends EntityRepository
{
    /**
     * Renvoie la liste des dossiers de l'utilisateur
     *
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return array
     */
    public function findFoldersListUser($userLogin)
    {
        return $this->createQueryBuilder('fus')
            ->select([
                'fol.folId',
                'fol.folLibelle',
                'fol.folIdOwner',
                'fol.folNbDoc'
            ])
            ->innerJoin('fus.fusFolder', 'fol')
            ->where('fus.fusUser = :userLogin')
            ->setParameter('userLogin', $userLogin)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Renvoie la liste des utilisateurs pour lesquels le dossier est partagé
     *
     * @param integer $folderId L'id du dossier
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return array
     */
    public function findUsersListIsSharedFolder($folderId, $userLogin)
    {
        return $this->createQueryBuilder('fus')
            ->select('usr.usrLogin')
            ->leftJoin('fus.fusUser', 'usr')
            ->where('fus.fusFolder = :folderId')
            ->setParameter('folderId', $folderId)
            ->andWhere('fus.fusUser != :userLogin')
            ->setParameter('userLogin', $userLogin)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Renvoie la liste des utilisateurs pour lesquels le dossier n'a pas été partagé
     *
     * @param integer $folderId L'id du dossier
     *
     * @return array
     */
    public function findUsersListIsUnsharedFolder($folderId)
    {
        return $this->_em->createQuery("SELECT usr.usrLogin FROM 'ApiBundle\Entity\UsrUsers' usr LEFT JOIN
            'ApiBundle\Entity\FusFolderUser' fus WITH usr.usrLogin = fus.fusUser AND fus.fusFolder = :folderId
             WHERE fus.fusId IS NULL AND usr.usrType = :usrType")
            ->setParameter('folderId', $folderId)
            ->setParameter('usrType', EnumLabelUserType::USR_USER)
            ->getArrayResult();
    }
}
