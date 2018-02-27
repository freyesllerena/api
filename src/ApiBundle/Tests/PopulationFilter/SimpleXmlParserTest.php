<?php

namespace ApiBundle\Tests\PopulationFilter;

use ApiBundle\PopulationFilter\SimpleXmlParser;
use ApiBundle\PopulationFilter\XmlVisitor;

class SimpleXmlParserTest extends \PHPUnit_Framework_TestCase implements XmlVisitor
{

    /**
     * @var string
     */
    protected $calledFunctions = '';

    /**
     * @var SimpleXmlParser
     */
    protected $parser;

    /**
     * Initialisation
     */
    protected function setUp()
    {
        parent::setUp();

        $this->calledFunctions = array();

        $this->parser = new SimpleXmlParser();
    }

    /**
     * Teste la parsing du xml d'un filtre par population
     *
     * @dataProvider getTestCasesForParse
     */
    public function testParse($xml, $result)
    {
        $parser = new SimpleXmlParser();
        $parser->parse($xml, $this);

        $this->assertEquals(
            $this->calledFunctions,
            $result
        );
    }

    /**
     * Différents jeux de tests pour tester le parsing du xml
     *
     * @return array
     */
    public function getTestCasesForParse()
    {
        return array(
            array(
                '',
                array(),
            ),
            array(
                '<comparator><field>ID_CODE_CLIENT</field><type>eq</type><value>TSI504</value></comparator>',
                array(
                    array('function' => 'visitComparatorNode', 'arguments' => array('ID_CODE_CLIENT','eq', array('TSI504')))
                ),
            ),
            array(
                '<comparator><field>ID_CODE_CLIENT</field><type>in</type><value>TSI504</value><value>TSI505</value></comparator>',
                array(
                    array('function' => 'visitComparatorNode', 'arguments' => array('ID_CODE_CLIENT','in', array('TSI504', 'TSI505')))
                ),
            ),
            array(
                '<operator><type>and</type><operand>
                    <comparator><field>ID_CODE_CLIENT</field><type>eq</type><value>TSI504</value></comparator></operand>
                </operator>',
                array(
                    array('function' => 'visitComparatorNode', 'arguments' => array('ID_CODE_CLIENT','eq', array('TSI504')))
                ),
            ),
            array(
                '<operator><type>not</type><operand>
                    <comparator><field>ID_CODE_CLIENT</field><type>eq</type><value>TSI504</value></comparator></operand>
                </operator>',
                array(
                    array('function' => 'visitOperatorNode', 'arguments' => array('not')),
                    array('function' => 'beforeOperandNode', 'arguments' => array('not')),
                    array('function' => 'visitComparatorNode', 'arguments' => array('ID_CODE_CLIENT','eq', array('TSI504'))),
                    array('function' => 'afterOperandNode', 'arguments' => array('not')),
                ),
            ),
            array(
                '<operator>
                    <type>or</type>
                    <operand>
                        <comparator>
                            <field>ID_CODE_SOCIETE</field>
                            <type>eq</type>
                            <value>IA</value>
                        </comparator>
                    </operand>
                    <operand>
                        <comparator>
                            <field>ID_CODE_SOCIETE</field>
                            <type>eq</type>
                            <value>IC</value>
                        </comparator>
                    </operand>
                </operator>',
                array(
                    array('function' => 'visitOperatorNode', 'arguments' => array('or')),
                    array('function' => 'beforeOperandNode', 'arguments' => array('or')),
                    array('function' => 'visitComparatorNode', 'arguments' => array('ID_CODE_SOCIETE','eq', array('IA'))),
                    array('function' => 'betweenOperandNode', 'arguments' => array('or')),
                    array('function' => 'visitComparatorNode', 'arguments' => array('ID_CODE_SOCIETE','eq', array('IC'))),
                    array('function' => 'afterOperandNode', 'arguments' => array('or')),
                ),
            ),
            array(
                '<operator><type>or</type></operator>',
                array()
            ),
            array(
                '<type_individuel>1</type_individuel>',
                array(
                    array('function' => 'visitTypeIndividuelNode', 'arguments' => array(1))
                ),
            ),
        );
    }

    /**
     * Mock du parcours d'un noeud "Comparator"
     *
     * @inheritdoc
     */
    public function visitComparatorNode($field, $operator, array $values)
    {
        $this->calledFunctions[] = array('function' => __FUNCTION__, 'arguments' => func_get_args());
    }

    /**
     * Mock du parcours d'un noeud "Operator"
     *
     * @inheritdoc
     */
    public function visitOperatorNode($type)
    {
        $this->calledFunctions[] = array('function' => __FUNCTION__, 'arguments' => func_get_args());
    }

    /**
     * Mock du parcours précédant un noeud Operand
     *
     * @inheritdoc
     */
    public function beforeOperandNode($type)
    {
        $this->calledFunctions[] = array('function' => __FUNCTION__, 'arguments' => func_get_args());
    }

    /**
     * Mock du parcours entre les noeuds "Operand"
     *
     * @inheritdoc
     */
    public function betweenOperandNode($type)
    {
        $this->calledFunctions[] = array('function' => __FUNCTION__, 'arguments' => func_get_args());
    }

    /**
     * Mock du parcours suivant un noeud "Operand"
     *
     * @inheritdoc
     */
    public function afterOperandNode($type)
    {
        $this->calledFunctions[] = array('function' => __FUNCTION__, 'arguments' => func_get_args());
    }

    /**
     * Mock du parcours d'un noeud "typ_individuel"
     *
     * @inheritdoc
     */
    public function visitTypeIndividuelNode($value)
    {
        $this->calledFunctions[] = array('function' => __FUNCTION__, 'arguments' => func_get_args());
    }
}
