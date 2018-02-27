<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\ProProcessus;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * ProProcessusRepository
 *
 * Class ProProcessusRepository
 * @package ApiBundle\Repository
 */
class ProProcessusRepository extends EntityRepository
{
    /**
     * Récupère la liste des processus et processus perso. d'un utilisateur
     *
     * @param string $userLogin Le login d'un utlisateur
     * @param array $drawersAllowed La liste des tiroirs autorisés
     *
     * @return array
     */
    public function getProcessList($userLogin, array $drawersAllowed)
    {
        $queryBuilder = $this->createQueryBuilder('pro');
        return $queryBuilder
            ->select([
                'pro.proId',
                'pro.proGroupe',
                'pro.proLibelle',
                'IDENTITY(pty.ptyType) AS ptyType'
            ])
            ->innerJoin('ApiBundle:PtyProcessusType', 'pty', 'WITH', 'pty.ptyProcessus = pro.proId')
            ->leftJoin('pro.proUser', 'usr')
            ->where('pro.proUser = :userLogin')
            ->setParameter('userLogin', $userLogin)
            ->andWhere($queryBuilder->expr()->in('pty.ptyType', $drawersAllowed))
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Vérifie que l'utilisateur n'est pas déjà propriétaire d'un processus portant le même nom
     *
     * @param ProProcessus $process Une instance de ProProcessus
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return mixed
     */
    public function findAnotherProcessLabel(ProProcessus $process, $userLogin)
    {
        $queryBuilder = $this->createQueryBuilder('pro')
            ->select('COUNT(pro)')
            ->where('pro.proLibelle = :label')
            ->andWhere('pro.proUser = :user')
            ->setParameters([
                'label' => $process->getProLibelle(),
                'user' => $userLogin
            ]);

        if ($process->getProId() != null) {
            $queryBuilder->andWhere('pro.proId != :id')
                ->setParameter('id', $process->getProId());
        }

        return $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();
    }
}
