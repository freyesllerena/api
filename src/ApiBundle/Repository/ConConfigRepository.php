<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\AbstractQuery;

/**
 * Repository de l'entité ConConfig
 *
 * @package ApiBundle\Repository
 */
class ConConfigRepository extends BaseRepository implements ConConfigRepositoryInterface
{

    /**
     * Vérifie si un paramètre existe dans la configuration
     *
     * @param mixed $offset Nom du paramètre
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        $query = $this->_em->createQuery(
            "SELECT con.conValeur FROM 'ApiBundle\Entity\ConConfig' con WHERE con.conVariable = :variable"
        );
        $query->setParameter('variable', $offset);
        $result = $query->getResult();
        return (bool) $result;
    }

    /**
     * Récupère la valeur d'un paramètre de configuration
     *
     * @param mixed $offset Nom du paramètre
     *
     * @return string
     */
    public function offsetGet($offset)
    {
        $query = $this->_em->createQuery(
            "SELECT con.conValeur FROM 'ApiBundle\Entity\ConConfig' con WHERE con.conVariable = :variable"
        );
        $query->setParameter('variable', $offset);
        $result = $query->getResult();
        if ($result) {
            return $result[0]['conValeur'];
        }
        return null;
    }

    /**
     * Modifie la valeur d'un paramètre de configuration
     *
     * @param mixed $offset Nom du paramètre
     * @param mixed $value Valeur du paramètre
     * @throws \Exception
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("Not implemented");
    }

    /**
     * Supprime un paramètre de configuration
     *
     * @param mixed $offset Nom du paramètre
     * @throws \Exception
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("Not implemented");
    }

    /**
     * Récupère la liste des paramètres de config de l'instance
     *
     * @param int $hydrateMode
     *
     * @return array
     */
    public function retrieveAll($hydrateMode = AbstractQuery::HYDRATE_ARRAY)
    {
        return $this->createQueryBuilder('con')
            ->getQuery()
            ->getResult($hydrateMode);
    }

    /**
     * Renvoie les droits définis au niveau de l'instance
     *
     * @return mixed
     */
    public function getInstanceAuthorizations()
    {
        $queryBuilder = $this->createQueryBuilder('con');
        $queryBuilder->select(array('con.conVariable', 'con.conValeur'));
        $queryBuilder->where("con.conVariable LIKE 'right_%' OR con.conVariable LIKE 'access_%'");

        $results = [];
        foreach ($queryBuilder->getQuery()->getResult() as $authorization) {
            $results[lcfirst(
                array_reduce(
                    explode('_', $authorization['conVariable']),
                    function ($habilitation, $value) {
                        $habilitation .= ucfirst($value);
                        return $habilitation;
                    }
                )
            )] = $authorization['conValeur'];
        }

        return $results;
    }
}
