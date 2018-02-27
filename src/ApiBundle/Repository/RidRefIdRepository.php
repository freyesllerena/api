<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

class RidRefIdRepository extends BaseRepository
{
    /**
     * Retourne un tableau des valeurs correspondantes au type demandé
     *
     * @param int       $hydrationMode Le type de résultat Doctrine que l'on veut
     * @param string    $type          Type de clé recherchée
     *
     * @return array
     */
    public function findByRidType($type, $hydrationMode = AbstractQuery::HYDRATE_ARRAY)
    {
        $queryBuilder = $this->createQueryBuilder('rid')
            ->select('rid.ridCode', 'rid.ridLibelle')
            ->where('rid.ridType = :type')
            ->setParameter('type', $type)
            ->orderBy('rid.ridCode', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true);
        $query->setResultCacheLifetime(43200);

        return $query->getResult($hydrationMode);
    }

    /**
     * Vérifie si le code PAC spécifié existe pour cette instance
     *
     * @param $pacId
     *
     * @return bool
     */
    public function isPacExists($pacId)
    {
        $queryBuilder = $this->createQueryBuilder('rid')
            ->select('rid.ridCode')
            ->where('rid.ridType = :type')
            ->andWhere('rid.ridCode = :pacId')
            ->setParameters(array('type' => 'CodeClient', 'pacId' => $pacId));

        return (bool)(count($queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY)) == 1);
    }

    /**
     * Retourne un tableau de toutes les valeurs de la table
     *
     * @return array
     */
    public function findAllAsArray()
    {
        return $this->createQueryBuilder('rid')
            ->select([
                'rid.ridCode',
                'rid.ridLibelle',
                'rid.ridType',
                'rid.ridIdCodeClient'
            ])
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Vérifie que les codes pacs existent
     *
     * @param array $pacs
     *
     * @return bool
     */
    public function checkPacs(array $pacs)
    {
        foreach ($pacs as $pac) {
            if (!$this->isPacExists($pac)) {
                return false;
            }
        }
        return true;
    }
}
