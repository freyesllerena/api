<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\IupInterupload;
use Doctrine\ORM\QueryBuilder;

class IupInteruploadRepository extends BaseRepository
{
    /**
     * Recherche de données interupload à partir du ticket et du challenge
     * @param $objParams
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findIupInteruploadByParams($objParams)
    {
        $builder = $this->createQueryBuilder('iup')
            ->where('iup.iupTicket = :ticket')
            ->setParameter('ticket', $objParams->ticket);
        if (isset($objParams->challenge) && $objParams->challenge != null) {
            $builder
                ->andWhere('iup.iupChallenge = :challenge')
                ->setParameter('chalenge', $objParams->challenge);
        }

        return $builder->getQuery()
            ->getOneOrNullResult();
    }
}