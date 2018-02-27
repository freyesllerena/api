<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\DicDictionnaire;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;

class DicDictionnaireRepository extends EntityRepository
{

    /**
     * Retourne un résultat des traductions pour une locale donnée
     *
     * @param QueryBuilder $queryBuilder Une instance de Doctrine query builder
     * @param string $locale La locale
     * @param int $hydrationMode Le type de résultat Doctrine que l'on veut
     *
     * @return array
     */
    public function getTranslations(QueryBuilder $queryBuilder, $locale, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        $query = $queryBuilder->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);

        return $query->getResult($hydrationMode);
    }

    /**
     * Retourne un tableau de toutes les valeurs traduites selon la locale
     *
     * @param string $locale La locale (par défaut: fr_FR)
     * @param string $device Le support (par défaut: des) des ou mob
     * @param int $hydrationMode Le type de résultat Doctrine que l'on veut
     *
     * @return array
     */
    public function findAll(
        $locale = DicDictionnaire::DEFAULT_DIC_LOCALE,
        $device = DicDictionnaire::DEFAULT_DIC_DEVICE,
        $hydrationMode = AbstractQuery::HYDRATE_ARRAY
    )
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->where('d.dicSupport = :support')
            ->setParameter('support', $device);

        return $this->getTranslations($queryBuilder, $locale, $hydrationMode);
    }
}
