<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\UsrUsers;
use Doctrine\ORM\QueryBuilder;

class PdaProfilDefAppliRepository extends BaseRepository implements PdaProfilDefAppliRepositoryInterface
{

    /**
     * Retourne les filtres applicatifs d'un utilisateur
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    public function findByUser(UsrUsers $user)
    {

        $dql = "SELECT pda FROM ApiBundle:PdaProfilDefAppli pda ".
            " JOIN ApiBundle:PdeProfilDef pde WITH (pde.pdeIdProfilDef = pda.pdaId AND pde.pdeType='appli') ".
            " JOIN ApiBundle:ProProfil pro WITH (pro.proId = pde.pdeId) ".
            " JOIN ApiBundle:PusProfilUser pus WITH (pro = pus.pusProfil) ".
            " WHERE pus.pusUser = :user";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);

        return $query->getResult();
    }

    /**
     * Renvoit la liste des filtres applicatifs avec le nombre d'utilisateurs associés
     *
     * @return array
     */
    public function selectAllAndCountUsers()
    {
        $queryBuilder = $this->createQueryBuilder('pda');
        $queryBuilder->select('pda AS appli');
        $queryBuilder->addSelect("COUNT(pus.pusUser) as nbr_users");
        $this->joinWithUser($queryBuilder);
        $queryBuilder->groupBy('appli');

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
            "pde.pdeIdProfilDef = pda.pdaId AND pde.pdeType='appli'"
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
