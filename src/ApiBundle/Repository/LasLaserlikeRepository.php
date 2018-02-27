<?php

namespace ApiBundle\Repository;

class LasLaserlikeRepository extends BaseRepository implements LasLaserlikeRepositoryInterface
{

    /**
     * Sélectionne à partir d'un numéro de PDF
     *
     * @param $numeroPdf
     */
    public function findOneByNumeroPdf($numeroPdf)
    {

        $queryBuilder = $this->createQueryBuilder('las');
        $queryBuilder->select('las')
            ->andWhere('las.lasDebut <= :numeroPdf AND :numeroPdf <= las.lasFin')
            ->setParameter('numeroPdf', $numeroPdf)
            ->setMaxResults(1)
        ;
        $results = $queryBuilder->getQuery()->getResult();
        return array_shift($results);
    }
}