<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * PtyProcessusTypeRepository
 *
 * Class PtyProcessusTypeRepository
 * @package ApiBundle\Repository
 */
class PtyProcessusTypeRepository extends EntityRepository
{
    /**
     * Supprime des tiroirs d'un processus
     *
     * @param array $drawersList Une liste de tiroirs à supprimer
     * @param integer $processId L'Id d'un processus
     *
     * @return mixed
     */
    public function removeDrawers(array $drawersList, $processId)
    {
        $queryBuilder = $this->createQueryBuilder('pty');
        return $queryBuilder
            ->delete()
            ->where($queryBuilder->expr()->in('pty.ptyType', $drawersList))
            ->andWhere('pty.ptyId = :id')
            ->setParameter('id', $processId)
            ->getQuery()
            ->execute();
    }

    /**
     * Récupère les tiroirs d'un processus
     *
     * @param integer $processId L'Id d'un processus
     *
     * @return array
     */
    public function findDrawers($processId)
    {
        return $this->createQueryBuilder('pty')
            ->select('typ.typCode')
            ->innerJoin('pty.ptyType', 'typ')
            ->where('pty.ptyProcessus = :id')
            ->setParameter('id', $processId)
            ->getQuery()
            ->getArrayResult();
    }
}
