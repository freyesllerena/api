<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CtyCompletudeTypeRepository
 *
 * Class CtyCompletudeTypeRepository
 * @package ApiBundle\Repository
 */
class CtyCompletudeTypeRepository extends EntityRepository
{
    /**
     * Supprime des tiroirs d'une complétude
     *
     * @param array $drawersList Une liste de tiroirs
     * @param integer $completudeId L'Id d'une complétude
     *
     * @return mixed
     */
    public function deleteDrawers(array $drawersList, $completudeId)
    {
        $queryBuilder = $this->createQueryBuilder('cty');
        return $queryBuilder
            ->delete()
            ->where($queryBuilder->expr()->in('cty.ctyType', $drawersList))
            ->andWhere('cty.ctyCompletude = :completude')
            ->setParameter('completude', $completudeId)
            ->getQuery()
            ->execute();
    }

    /**
     * Récupère la liste des tiroirs d'une complétude
     *
     * @param integer $completudeId L'Id d'une complétude
     *
     * @return array
     */
    public function findDrawers($completudeId)
    {
        return $this->createQueryBuilder('cty')
            ->select('typ.typCode')
            ->innerJoin('cty.ctyType', 'typ')
            ->where('cty.ctyCompletude = :completude')
            ->setParameter('completude', $completudeId)
            ->getQuery()
            ->getArrayResult();
    }
}
