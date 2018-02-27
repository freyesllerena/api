<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\PdeProfilDef;
use ApiBundle\Entity\ProProfil;
use ApiBundle\Entity\UsrUsers;

class PdeProfilDefRepository extends BaseRepository
{
    /**
     * Renvoie les profils associés à un utilisateur
     *
     * @param UsrUsers $user
     * @return array
     */
    public function findByUser(UsrUsers $user)
    {
        $queryBuilder = $this->createQueryBuilder('pde');
        $queryBuilder->leftJoin('ApiBundle:ProProfil', 'pro', 'WITH', 'pde.pdeId = pro.proId');
        $queryBuilder->leftJoin('ApiBundle:PusProfilUser', 'pus', 'WITH', 'pro = pus.pusProfil');
        $queryBuilder->where('pus.pusUser = :user');

        $queryBuilder->setParameter('user', $user);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Met à jour les filtres applicatifs et population d'un profil
     *
     * @param ProProfil $profil Profil à mettre à jour
     * @param array     $applis Filtres applicatifs
     * @param array     $habis  Filtres par populations
     */
    public function updateByProfil(ProProfil $profil, array $applis = array(), array $habis = array())
    {
        $dql = "DELETE FROM ApiBundle:PdeProfilDef pde WHERE pde.pdeId = :proId";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('proId', $profil->getProId());
        $query->execute();

        $data = array(
            'appli' => $applis,
            'habi' => $habis,
        );

        foreach ($data as $type => $filters) {
            foreach ($filters as $filter) {
                $pde = new PdeProfilDef();
                $pde->setPdeId($profil->getProId());
                $pde->setPdeIdProfilDef($filter);
                $pde->setPdeType($type);
                $this->_em->persist($pde);
            }
        }

        $this->_em->flush();
    }
}
