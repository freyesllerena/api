<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\PopulationFilter\DoctrineExpressionBuilder;
use ApiBundle\PopulationFilter\SimpleXmlParser;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

class IinIdxIndivRepository extends BaseRepository
{

    /**
     * Génère la requête des filtres par population pour un utilisateur donné
     *
     * @param UsrUsers $user
     */
    public function createUserHabilitationsQuery(UsrUsers $user)
    {
        $expressions = array();
        $parameters = array();

        $pdhs = $this->getEntityManager()
            ->getRepository('ApiBundle:PdhProfilDefHabi')
            ->findByUser($user);
        /* @var $pdhs PdhProfilDefHabi[] */
        foreach ($pdhs as $n => $pdh) {
            list($expression, $params) = $this->createHabilitationExpression($pdh, 'habi_'.$n);
            if ($expression) {
                $expressions[] = $expression;
                $parameters += $params;
            }
        }

        $queryBuilder = $this->createQueryBuilder('iin');
        $queryBuilder->select('iin.iinId');
        if ($expressions) {
            $queryBuilder->andWhere(implode(' AND ', $expressions));
            foreach ($parameters as $name => $value) {
                $queryBuilder->setParameter($name, $value);
            }
        } else {
            $queryBuilder->andWhere(implode(' 0 '));
        }

        return $queryBuilder;
    }

    /**
     * Génère l'expression DQL d'un filtre par population donné
     *
     * @param PdhProfilDefHabi $pdh         Le filtre par population
     * @param string           $paramPrefix Le préfixe des paramètres
     *
     * @return array           renvoit l'expression et les paramètres associés
     * @throws \Exception
     */
    protected function createHabilitationExpression(PdhProfilDefHabi $pdh, $paramPrefix = '')
    {
        $xmlParser = new SimpleXmlParser();

        $expressionBuilder = new DoctrineExpressionBuilder();
        $expressionBuilder->setTableAlias('iin');
        $expressionBuilder->setColumnMapping($this->getColumnMapping());
        $expressionBuilder->setParameterPrefix($paramPrefix . '_');
        $xmlParser->parse($pdh->getPdhHabilitationC(), $expressionBuilder);

        return array($expressionBuilder->getExpression(), $expressionBuilder->getParameters());
    }


    /**
     * Retourne la liste des salariés classés par matricule RH
     *
     * @param null|array $fields Une liste des champs à sélectionner
     * @param int $hydrationMode Le type de résultat Doctrine que l'on veut
     *
     * @return array
     */
    public function findAllOrdered($fields = null, $hydrationMode = AbstractQuery::HYDRATE_ARRAY)
    {
        $queryBuilder = $this->createQueryBuilder('iin')
            ->where('iin.iinIdNumMatriculeRh != \'\'');

        if ($fields) {
            $queryBuilder->select($fields);
        }

        $queryBuilder->orderBy('iin.iinIdNumMatriculeRh', 'ASC');

        return $queryBuilder->getQuery()->getResult($hydrationMode);
    }
}
