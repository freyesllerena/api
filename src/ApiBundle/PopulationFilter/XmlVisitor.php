<?php

namespace ApiBundle\PopulationFilter;

/**
 * Fonctions obligatoires pour parcourir le xml d'un filtre par population
 *
 * @package ApiBundle\PopulationFilter
 */
interface XmlVisitor
{
    /**
     * @param string $field
     * @param string $operator
     * @param array $values
     */
    public function visitComparatorNode($field, $operator, array $values);

    /**
     * @param string $type
     */
    public function visitOperatorNode($type);

    /**
     * @param string $type
     */
    public function beforeOperandNode($type);

    /**
     * @param string $type
     */
    public function betweenOperandNode($type);

    /**
     * @param string $type
     */
    public function afterOperandNode($type);

    /**
     * @param string $value
     */
    public function visitTypeIndividuelNode($value);
}
