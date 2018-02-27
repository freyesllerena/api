<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\BfoBaseFolder;
use ApiBundle\Entity\FolFolder;
use Doctrine\ORM\EntityRepository;

class FdoFolderDocRepository extends EntityRepository
{
    /**
     * Renvoie les Ids des documents associés à un dossier
     *
     * @param integer $folderId L'id du dossier
     *
     * @return array
     */
    public function getFolderDocuments($folderId)
    {
        return $this->createQueryBuilder('fdo')
            ->select('ifp.ifpId')
            ->innerJoin('fdo.fdoDoc', 'ifp')
            ->where('fdo.fdoFolder = :folderId')
            ->setParameter('folderId', $folderId)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Supprime une liste de documents d'un dossier
     *
     * @param FolFolder $folder Une instance de FolFolder
     * @param array $documents Un tableau d'Ids Document à supprimer
     *
     * @return mixed
     */
    public function removeDocuments(BfoBaseFolder $folder, array $documents)
    {
        $queryBuilder = $this->createQueryBuilder('fdo')
            ->delete()
            ->where('fdo.fdoFolder = :folderId')
            ->setParameter('folderId', $folder->getFolId());
        // Ajout des Ids à supprimer
        $condIn = $queryBuilder->expr()->in('fdo.fdoDoc', $documents);

        return $queryBuilder->andWhere($condIn)
            ->getQuery()
            ->execute();
    }

    /**
     * Compte le nombre de documents d'un dossier
     *
     * @param int $folderId L'id du dossier
     *
     * @return mixed
     */
    public function getFolderNbDocuments($folderId)
    {
        return $this->createQueryBuilder('fdo')
            ->select('COUNT(fdo)')
            ->where('fdo.fdoFolder = :folderId')
            ->setParameter('folderId', $folderId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
