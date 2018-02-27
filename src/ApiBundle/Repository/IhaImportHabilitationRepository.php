<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IhaImportHabilitationRepository extends EntityRepository
{
    /**
     * Renvoie la liste des imports en masse des habilitations
     *
     * @return array
     */
    public function findHabilitationImportList()
    {
        return $this->createQueryBuilder('iha')
            ->select([
                'iha.ihaTraite',
                'iha.ihaSucces',
                'iha.ihaErreur',
                'iha.ihaCreatedAt',
                'rap.rapEtat',
                'rap.rapId'
            ])
            ->join('iha.ihaRapport', 'rap')
            ->orderBy('iha.ihaCreatedAt', 'desc')
            ->getQuery()
            ->getResult();
    }
}
