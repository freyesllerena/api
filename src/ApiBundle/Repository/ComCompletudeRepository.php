<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\ComCompletude;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\IinIdxIndiv;
use ApiBundle\Enum\EnumLabelEmployeeFilterType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * ComCompletudeRepository
 *
 * Class ComCompletudeRepository
 * @package ApiBundle\Repository
 */
class ComCompletudeRepository extends EntityRepository
{
    /**
     * Contrôle que le propriétaire n'a pas de complétude portant le libellé indiqué autre que la complétude en cours
     *
     * @param ComCompletude $completude Une instance de ComCompletude
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return mixed
     */
    public function findAnotherCompletudeLabel(ComCompletude $completude, $userLogin)
    {
        $builder = $this->createQueryBuilder('com')
            ->select('COUNT(com)')
            ->where('com.comLibelle = :label')
            ->setParameter('label', $completude->getComLibelle())
            ->andWhere('com.comUser = :user')
            ->setParameter('user', $userLogin);

        if ($completude->getComIdCompletude() != null) {
            $builder->andWhere('com.comIdCompletude != :id')
                ->setParameter('id', $completude->getComIdCompletude());
        }

        return $builder->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Renvoie la liste des complétudes de l'utilisateur
     *
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return array
     */
    public function findCompletudeList($userLogin)
    {
        return $this->createQueryBuilder('com')
            ->select([
                'com.comIdCompletude',
                'com.comLibelle',
                'com.comPrivee',
                'com.comAuto',
                'com.comEmail',
                'com.comPeriode',
                'com.comAvecDocuments',
                'com.comPopulation',
                'usr.usrLogin',
                'usr.usrNom',
                'usr.usrPrenom',
                'IDENTITY(cty.ctyType) AS ctyType'
            ])
            ->innerJoin('ApiBundle:CtyCompletudeType', 'cty', 'WITH', 'com.comIdCompletude = cty.ctyCompletude')
            ->leftJoin('com.comUser', 'usr')
            ->where('com.comUser = :user AND com.comPrivee = 1')
            ->orWhere('com.comPrivee = 0')
            ->setParameter('user', $userLogin)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Recherche les employés sans documents
     *
     * @param \stdClass $params
     * @param array $fields
     *
     * @return array
     */
    public function findCompletudeWithoutDoc(\stdClass $params, array $fields)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from('ApiBundle:IinIdxIndiv', 'iin');
        $queryBuilder->select(
            'IDENTITY(cty.ctyType) AS ctyType, iin.'
            . implode(
                ', iin.',
                $fields
            )
        );
        $queryBuilder = $this->buildCompletudeResultWithoutDoc($params, $queryBuilder);
        if (isset($params->start)) {
            $queryBuilder->setFirstResult($params->start);
        }
        if (isset($params->limit)) {
            $queryBuilder->setMaxResults($params->limit);
        }

        return
            $queryBuilder
                ->getQuery()
                ->getArrayResult();
    }

    /**
     * Compte le nombre d'employés sans documents
     *
     * @param \stdClass $params
     *
     * @return mixed
     */
    public function countCompletudeWithoutDoc(\stdClass $params)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from('ApiBundle:IinIdxIndiv', 'iin')
            ->select('COUNT(iin)');
        $queryBuilder = $this->buildCompletudeResultWithoutDoc($params, $queryBuilder, true);

        return
            $queryBuilder
                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * Construit la requête de complétude sans documents
     *
     * @param \stdClass $params
     * @param QueryBuilder $queryBuilder
     * @param bool $count
     *
     * @return QueryBuilder|mixed
     */
    private function buildCompletudeResultWithoutDoc(\stdClass $params, QueryBuilder $queryBuilder, $count = false)
    {
        $queryBuilder
            ->join('ApiBundle:CtyCompletudeType', 'cty', 'WITH', 'cty.ctyCompletude = :completude')
            ->leftJoin(
                'ApiBundle:IfpIndexfichePaperless',
                'ifp',
                'WITH',
                'iin.iinIdNumMatriculeRh = ifp.ifpIdNumMatriculeRh AND ifp.ifpCodeDocument = cty.ctyType'
            )
            ->where('ifp.ifpId IS NULL')
            ->setParameter('completude', $params->node->value);

        $queryBuilder = $this->queryFilterEmployeePopulation($params->node->population, $queryBuilder);
        // Traitement des paramètres Fields Iin
        if ($params->fields) {
            $queryBuilder = $this->getEntityManager()
                ->getRepository('ApiBundle:IfpIndexfichePaperless')
                ->searchInFields($params->fields, $queryBuilder, 'iin');
        }
        if (!$count) {
            if (is_object($params->sorts)) {
                foreach ($params->sorts as $keySorts => $propertySorts) {
                    $queryBuilder->addOrderBy('iin.' . $keySorts, $propertySorts->dir);
                }
            }
        }
        return $queryBuilder;
    }

    /**
     * Ajoute le filtre salariés présents/sortis
     *
     * @param $population
     * @param QueryBuilder $queryBuilder
     *
     * @return QueryBuilder
     */
    public function queryFilterEmployeePopulation($population, QueryBuilder $queryBuilder)
    {
        if ($population == EnumLabelEmployeeFilterType::PRESENT_POP) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    'iin.iinIdDateSortie IS NULL',
                    'DATE(iin.iinIdDateSortie) >= DATE(CURRENT_TIMESTAMP())'
                )
            );
        } elseif ($population == EnumLabelEmployeeFilterType::OUT_POP) {
            $queryBuilder->andWhere('DATE(iin.iinIdDateSortie) < DATE(CURRENT_TIMESTAMP())');
        }
        return $queryBuilder;
    }
}
