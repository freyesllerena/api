<?php

namespace ApiBundle\Tests\Model;

use ApiBundle\Enum\EnumLabelModeHabilitationType;
use ApiBundle\Model\Comparator;
use ApiBundle\Model\Operator;
use ApiBundle\PopulationFilter\DoctrineExpressionBuilder;
use ApiBundle\Visitor\PopulationFilterDoctrineExpressionBuilder;

/**
 * Teste le générateur de requête d'un filtre par population
 *
 * @package ApiBundle\Tests\Model
 */
class DoctrineExpressionBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Teste la génération de requête sur un noeud "comparator"
     *
     * @dataProvider getDataForVisitingComparatorNodeTest
     *
     * @param string $field
     * @param string $operator
     * @param array  $values
     * @param string $expression
     * @param array  $parameters
     *
     * @throws \Exception
     */
    public function testVisitComparatorNode($field, $operator, $values, $expression, $parameters)
    {
        $exprBuilder = new DoctrineExpressionBuilder('typType.typIndividuel = 1 AND ');
        $exprBuilder->setTableAlias('iin');
        $exprBuilder->setColumnMapping(array(
            'ID_NOM_SALARIE' => 'iinIdNomSalarie',
            'ID_CODE_CLIENT' => 'iinCodeClient',
        ));

        $exprBuilder->visitComparatorNode($field, $operator, $values);

        $this->assertEquals($expression, $exprBuilder->getExpression());

        $this->assertEquals($parameters, $exprBuilder->getParameters());

    }

    /**
     * Jeu de données pour tester la génération de requête sur un node "Comparator"
     *
     * @return array
     */
    public function getDataForVisitingComparatorNodeTest()
    {
        return array(
            array(
                "ID_NOM_SALARIE", "eq", ['johndoe'],
                'typType.typIndividuel = 1 AND iin.iinIdNomSalarie = :param_0',
                array('param_0' => 'johndoe')
            ),
            array(
                "ID_NOM_SALARIE", "nq", ['sbrion'],
                'typType.typIndividuel = 1 AND iin.iinIdNomSalarie != :param_0',
                array('param_0' => 'sbrion')
            ),
            array(
                "ID_CODE_CLIENT", "lt", [4],
                'typType.typIndividuel = 1 AND iin.iinCodeClient < :param_0',
                array('param_0' => 4)
            ),
            array(
                "ID_CODE_CLIENT", "lte", [4],
                'typType.typIndividuel = 1 AND iin.iinCodeClient <= :param_0',
                array('param_0' => 4)
            ),
            array(
                "ID_CODE_CLIENT", "gt", [4],
                'typType.typIndividuel = 1 AND iin.iinCodeClient > :param_0',
                array('param_0' => 4)
            ),
            array(
                "ID_CODE_CLIENT", "gte", [4],
                'typType.typIndividuel = 1 AND iin.iinCodeClient >= :param_0',
                array('param_0' => 4)
            ),
            array(
                "ID_CODE_CLIENT", "between", [1, 10],
                'typType.typIndividuel = 1 AND iin.iinCodeClient BETWEEN :param_0 AND :param_1',
                array('param_0' => 1, 'param_1' => 10)
            ),
            array(
                "ID_CODE_CLIENT", "in", [4, 5, 6],
                'typType.typIndividuel = 1 AND iin.iinCodeClient IN (:param_0, :param_1, :param_2)',
                array('param_0' => 4, 'param_1' => 5, 'param_2' => 6)
            ),
        ) ;
    }

    /**
     * Erreur si le type d'un comparateur est invalide
     *
     * @expectedException \Exception
     */
    public function testVisitComparatorNodeWithInvalidType()
    {
        $exprBuilder = new DoctrineExpressionBuilder('typType.typIndividuel = 1 AND ');

        $exprBuilder->visitComparatorNode("ID_CODE_CLIENT", "???", [4]);
    }

    /**
     * Erreur si un comparateur "between" a moins de deux valeurs
     *
     * @expectedException \Exception
     */
    public function testVisitComparatorNodeWhenBetweenHasOneValue()
    {
        $exprBuilder = new DoctrineExpressionBuilder('typType.typIndividuel = 1 AND ');

        $exprBuilder->visitComparatorNode("ID_CODE_CLIENT", "between", [4]);
    }

    /**
     * Erreur si les valeurs d'un comparateur "in" sont vides
     *
     * @expectedException \Exception
     */
    public function testVisitComparatorNodeWhenInHaveEmptyValues()
    {
        $exprBuilder = new DoctrineExpressionBuilder('typType.typIndividuel = 1 AND ');

        $exprBuilder->visitComparatorNode("ID_CODE_CLIENT", "in", []);
    }

    /**
     * Teste génération de requête avant le parcours des enfants d'un node
     *
     * @dataProvider getDataForBeforeChildrenTest
     *
     * @param $type
     * @param $expectedQuery
     */
    public function testBeforeOperand($type, $expectedQuery)
    {
        $exprBuilder = new DoctrineExpressionBuilder('typType.typIndividuel = 1 AND ');

        $exprBuilder->beforeOperandNode($type);

        $this->assertEquals($expectedQuery, $exprBuilder->getExpression());

    }

    /**
     * Jeu de données pour tester le début d'un parcours des enfants d'un node
     *
     * @return array
     */
    public function getDataForBeforeChildrenTest()
    {
        return [
            ['and', "typType.typIndividuel = 1 AND ("],
            ['or', "typType.typIndividuel = 1 AND ("],
            ['not', "typType.typIndividuel = 1 AND NOT ("],
        ];
    }

    /**
     * Teste génération de requête pendant le parcours des enfants d'un node
     *
     * @dataProvider getDataForBetweenChildrenTest
     *
     * @param string $type
     * @param string $expectedQuery
     */
    public function testBetweenOperand($type, $expectedQuery)
    {
        $exprBuilder = new DoctrineExpressionBuilder("typType.typIndividuel = 1 AND (iid.type = 'n'");

        $exprBuilder->betweenOperandNode($type);

        $this->assertEquals($expectedQuery, $exprBuilder->getExpression());
    }

    /**
     * Jeu de données pour tester le parcours entre les enfants d'un node
     *
     * @return array
     */
    public function getDataForBetweenChildrenTest()
    {
        return [
            ['and', "typType.typIndividuel = 1 AND (iid.type = 'n' AND "],
            ['or', "typType.typIndividuel = 1 AND (iid.type = 'n' OR "],
            ['not', "typType.typIndividuel = 1 AND (iid.type = 'n' OR "],
        ];
    }


    /**
     * Teste génération de requête après le parcours des enfants d'un node
     */
    public function testAfterOperand()
    {
        $exprBuilder = new DoctrineExpressionBuilder("typType.typIndividuel = 1 AND (iid.type = 'n' OR iid.name = 'some value'");

        $exprBuilder->afterOperandNode('and');

        $expectedQuery = "typType.typIndividuel = 1 AND (iid.type = 'n' OR iid.name = 'some value')";

        $this->assertEquals($expectedQuery, $exprBuilder->getExpression());
    }
}
