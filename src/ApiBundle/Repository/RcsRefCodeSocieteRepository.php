<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

class RcsRefCodeSocieteRepository extends BaseRepository
{
    /**
     * Retourne un tableau des valeurs correspondantes au type demandé
     *
     * @param int $hydrationMode Le type de résultat Doctrine que l'on veut
     * @param String $pacId      Code pac
     *
     * @return array
     */
    public function findByRcsIdCodeClient($pacId, $hydrationMode = AbstractQuery::HYDRATE_ARRAY)
    {
        $queryBuilder = $this->createQueryBuilder('rcs')
            ->select('rcs.rcsSiren', 'rcs.rcsLibelle', 'rcs.rcsSiren')
            ->where('rcs.rcsIdCodeClient = :pacId')
            ->setParameter('pacId', $pacId)
            ->orderBy('rcs.rcsLibelle', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true);
        $query->setResultCacheLifetime(43200);

        return array_column($query->getResult($hydrationMode), 'rcsLibelle', 'rcsSiren');
    }
}
