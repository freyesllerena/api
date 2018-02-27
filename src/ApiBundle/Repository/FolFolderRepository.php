<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\FolFolder;
use Doctrine\ORM\EntityRepository;

class FolFolderRepository extends EntityRepository
{
    /**
     * Contrôle que le propriétaire n'a pas de dossier portant le libellé indiqué autre que le folder en cours
     *
     * @param FolFolder $folder Une instance de FolFolder
     * @param $userLogin
     *
     * @return mixed
     */
    public function findAnotherFolderLabel(FolFolder $folder, $userLogin)
    {
        $queryBuilder = $this->createQueryBuilder('fol')
            ->select('COUNT(fol)')
            ->where('fol.folLibelle = :label')
            ->setParameter('label', $folder->getFolLibelle())
            ->andWhere('fol.folIdOwner = :user')
            ->setParameter('user', $userLogin);

        if ($folder->getFolId() != null) {
            $queryBuilder->andWhere('fol.folId != :id')
                ->setParameter('id', $folder->getFolId());
        }

        return $queryBuilder->getQuery()
            ->getSingleScalarResult();
    }
}
