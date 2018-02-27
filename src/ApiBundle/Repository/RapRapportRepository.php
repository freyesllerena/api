<?php

namespace ApiBundle\Repository;

use ApiBundle\Enum\EnumLabelTypeRapportType;
use Doctrine\ORM\EntityRepository;

class RapRapportRepository extends EntityRepository
{
    /**
     * Renvoie la liste des rapports de l'import de masse
     *
     * @return array
     */
    public function findMassImportList()
    {
        return $this->createQueryBuilder('rap')
            ->select([
                'rap.rapId',
                'rap.rapLibelleFic',
                'rap.rapEtat',
                'rap.rapCreatedAt'
            ])
            ->where('rap.rapTypeRapport = :type')
            ->setParameter('type', EnumLabelTypeRapportType::IMPORT_TYPE)
            ->orderBy('rap.rapCreatedAt', 'desc')
            ->getQuery()
            ->getArrayResult();
    }
}
