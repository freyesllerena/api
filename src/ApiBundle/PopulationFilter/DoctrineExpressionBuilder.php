<?php

namespace ApiBundle\PopulationFilter;

class DoctrineExpressionBuilder implements XmlVisitor
{

    /**
     * @var string
     */
    protected $expression;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $parameterPrefix = 'param_';

    /**
     * @var string
     */
    protected $tableAlias;

    /**
     * @var array
     */
    protected $columnMapping;

    /**
     * DoctrineExpressionBuilder constructor.
     * @param string $expression
     */
    public function __construct($expression = "")
    {
        $this->expression = $expression;
    }

    /**
     * @param array $columnMapping
     */
    public function setColumnMapping($columnMapping)
    {
        $this->columnMapping = $columnMapping;
    }

    /**
     * @param $parameterPrefix
     * @return $this
     */
    public function setParameterPrefix($parameterPrefix)
    {
        $this->parameterPrefix = $parameterPrefix;
        return $this;
    }

    /**
     * Renvoit l'expressione générée
     *
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Renvoit les paramètres générés automatiquement
     *
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $tableAlias
     */
    public function setTableAlias($tableAlias)
    {
        $this->tableAlias = $tableAlias;
    }

    /**
     * Met à jour la requête quand on parcour un noead "Comparator"
     *
     * @param string $field    nom du champ
     * @param string $operator type d'operateur (eq, neq, lte, gte, etc ...)
     * @param array  $values   valeurs
     * @throws \Exception
     */
    public function visitComparatorNode($field, $operator, array $values)
    {
        $operatorsMapping = array(
            'eq' => '=',
            'nq' => '!=',
            'lt' => '<',
            'lte' => '<=',
            'gt' => '>',
            'gte' => '>=',
        );

        if (isset($operatorsMapping[$operator])) {
            $this->expression .= $this->createComparatorExpression($field, $operatorsMapping[$operator], $values[0]);
        } elseif ($operator == 'between') {
            if (count($values) < 2) {
                throw new \Exception("Operateur '%s' nécessite deux valeurs. Une seule valeur a été reçue");
            }
            $this->expression .= $this->createBetweenExpression($field, $values[0], $values[1]);
        } elseif ($operator == 'in') {
            if (count($values) == 0) {
                throw new \Exception(sprintf(
                    "L'opérateur '%s' nécessite au moins une valeur. Aucune valeur reçue",
                    $operator
                ));
            }

            $this->expression .= $this->createInExpression($field, $values);
        } else {
            throw new \Exception(sprintf("Operateur '%s' non traité", $operator));
        }
    }

    /**
     * Crée une expression de comparaison (=, !=, <, >, etc.)
     *
     * @param string $field
     * @param string $operator
     * @param string $value
     * @return string
     */
    protected function createComparatorExpression($field, $operator, $value)
    {
        $field = $this->tableAlias.'.'.$this->convertFieldToProperty($field);
        return $field . ' ' . $operator . ' '.$this->addParameter($value);
    }


    /**
     * Créé une expression BETWEEN
     *
     * @param string $field  nom du champ
     * @param string $value1 valeur minimum
     * @param string $value2 valeur maximum
     * @return string
     */
    protected function createBetweenExpression($field, $value1, $value2)
    {
        $field = $this->tableAlias.'.'.$this->convertFieldToProperty($field);
        return $field . ' BETWEEN ' .$this->addParameter($value1). ' AND '.$this->addParameter($value2);
    }

    /**
     * Créé une expression IN
     *
     * @param string $field
     * @param array $values
     * @return string
     */
    protected function createInExpression($field, $values)
    {
        $parameters = array_map(array($this, 'addParameter'), $values);

        $field = $this->tableAlias.'.'.$this->convertFieldToProperty($field);
        return $field . ' IN (' . implode(', ', $parameters).')';
    }

    /**
     * Ajoute un paramètre
     *
     * @param string $value
     * @return string Nom du nouveau paramètre
     */
    protected function addParameter($value)
    {
        $name = $this->parameterPrefix . count($this->parameters);
        $this->parameters[$name] = $value;
        return ':'.$name;
    }

    /**
     * Modifie la requête quand on parcours un noeud "operator"
     *
     * @inheritdoc
     * @param string $type
     */
    public function visitOperatorNode($type)
    {
        // Pour l'instant, la requête n'est pas modifié lorsqu'on parcourt ce type de noeud
    }

    /**
     * Modifie la requête avant le parcours d'un noeud "operator"
     *
     * @inheritdoc
     * @param string $type
     */
    public function beforeOperandNode($type)
    {
        if ($type == 'not') {
            $this->expression .= 'NOT (';
        } else {
            $this->expression .= '(';
        }
    }

    /**
     * Modifie la requête pendant le parcours d'un noeud "operator"
     *
     * @inheritdoc
     * @param string $type
     */
    public function betweenOperandNode($type)
    {
        if ($type == 'and') {
            $this->expression .= ' AND ';
        } else {
            $this->expression .= ' OR ';
        }
    }

    /**
     * Modifie la requête après le parcours d'un noeud "operator"
     *
     * @inheritdoc
     * @param string $type
     */
    public function afterOperandNode($type)
    {
        $this->expression .= ')';
    }

    /**
     * Modifie la requête pendant le parcours d'un noeud "type_individuel"
     *
     * @inheritdoc
     * @param $value
     */
    public function visitTypeIndividuelNode($value)
    {
        $this->expression .= $this->aliases['typ'].'.'.
            $this->convertFieldName('individuel', 'typ'). ' = '.$this->addParameter($value);
    }

    /**
     * Convertit le nom de champ au format Doctrine
     *
     * @param $field
     * @return string
     */
    protected function convertFieldToProperty($field)
    {
        if (isset($this->columnMapping[$field])) {
            return $this->columnMapping[$field];
        }
        return $field;
    }
}
