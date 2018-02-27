<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Entity\TypType;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TypTypeRepository extends EntityRepository
{
    /**
     * Retourne les tiroirs et niveaux du plan de classement
     *
     * @param int $hydrationMode Le type de résultat Doctrine que l'on veut
     *
     * @return array
     */
    public function findAll($hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        $queryBuilder = $this->createQueryBuilder('typ')
            ->orderBy('typ.typNumOrdre1', 'ASC')
            ->addOrderBy('typ.typNumOrdre2', 'ASC')
            ->addOrderBy('typ.typNumOrdre3', 'ASC')
            ->addOrderBy('typ.typNumOrdre4', 'ASC');

        return $queryBuilder->getQuery()->getResult($hydrationMode);
    }

    /**
     * Rechercxhe du type de document
     * @param $codeClient
     * @return mixed
     */
    // @TODO: La liste des tiroirs classés par type est déjà chargée dans memcache lors de la construction du plan de classement !! Merci d'utiliser la méthode adéquate!!!!
    public function findTypIndividuelByTypeCode($codeClient)
    {
        $queryBuilder = $this->createQueryBuilder('typ')
            ->select('typ.typIndividuel')
            ->where('typ.typCode = :code')
            ->setParameter('code', $codeClient);

        return
            $queryBuilder
                ->getQuery()
                ->getSingleScalarResult()
            ;
    }
}
