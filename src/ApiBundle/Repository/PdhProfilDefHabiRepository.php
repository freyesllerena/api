<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\UsrUsers;
use Doctrine\ORM\QueryBuilder;

class PdhProfilDefHabiRepository extends BaseRepository implements PdhProfilDefHabiRepositoryInterface
{

    /**
     * Retourne les filtres par population d'un utilisateur
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    public function findByUser(UsrUsers $user)
    {

        $queryBuilder = $this->createQueryBuilder('pdh');
        $queryBuilder->select('pdh');
        $this->joinWithUser($queryBuilder);
        $queryBuilder->where('pus.pusUser = :user');

        $query = $queryBuilder->getQuery();
        $query->setParameter('user', $user);

        return $query->getResult();
    }

    /**
     * Renvoit la liste des filtres par populations avec le nombre d'utilisateurs associés
     *
     * @return array
     */
    public function selectAllAndCountUsers()
    {
        $queryBuilder = $this->createQueryBuilder('pdh');
        $queryBuilder->select('pdh AS habi');
        $queryBuilder->addSelect("COUNT(pus.pusUser) as nbr_users");
        $this->joinWithUser($queryBuilder);
        $queryBuilder->groupBy('habi');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Créé une jointure sur la table UsrUsers
     *
     * @param QueryBuilder $queryBuilder
     */
    protected function joinWithUser(QueryBuilder $queryBuilder)
    {
        $queryBuilder->leftJoin(
            'ApiBundle:PdeProfilDef',
            'pde',
            'WITH',
            "pde.pdeIdProfilDef = pdh.pdhId AND pde.pdeType='habi'"
        );
        $queryBuilder->leftJoin(
            'ApiBundle:ProProfil',
            'pro',
            'WITH',
            'pro.proId = pde.pdeId'
        );
        $queryBuilder->leftJoin(
            'ApiBundle:PusProfilUser',
            'pus',
            'WITH',
            'pro = pus.pusProfil'
        );
    }
}
