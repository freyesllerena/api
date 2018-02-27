<?php

namespace ApiBundle\Manager;

/**
 * Tests sur filtre par population
 *
 * @package ApiBundle\Manager
 */
class PopulationFilterManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PopulationFilterManager
     */
    protected $manager;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->manager = new PopulationFilterManager();
    }

    /**
     * @dataProvider getUnserializeData
     *
     * @param $xml
     * @param $expected
     */
    public function testUnserialize($xml, $data)
    {
        $this->assertEquals(
            $data,
            $this->manager->unserialize($xml)
        );
    }

    /**
     * @dataProvider getUnserializeData
     *
     * @param string $xml
     * @param array $data
     */
    public function testSerialize($xml, $data)
    {
        $this->assertEquals(
            $xml,
            $this->manager->serialize($data)
        );
    }

    public function getUnserializeData()
    {
        return array(
            array('', array()),
            array(
                '<operator>'.
                    '<type>and</type>'.
                    '<operand>'.
                    '</operand>'.
                '</operator>'
                ,
                array(
                    'codeMetadata' => null,
                    'typeRegle' => 'and',
                    'values' => array(),
                    'operator' => null,
                    'children' => array(),
                )
            ),
            array(
                '<operator>'.
                    '<type>and</type>'.
                    '<operand>'.
                        '<comparator>'.
                            '<field>ID_NOM_SALARIE</field>'.
                            '<type>eq</type>'.
                            '<value>SMITH</value>'.
                        '</comparator>'.
                    '</operand>'.
                '</operator>'
            ,
                array(
                    'codeMetadata' => null,
                    'typeRegle' => 'and',
                    'values' => array(),
                    'operator' => null,
                    'children' => [
                        array (
                            'codeMetadata' => 'ID_NOM_SALARIE',
                            'typeRegle' => null,
                            'values' => array("SMITH"),
                            'operator' => 'eq',
                            'children' => array(),
                        )
                    ]
                )
            ),
            array(
                '<operator>'.
                    '<type>and</type>'.
                    '<operand>'.
                        '<comparator>'.
                            '<field>ID_NOM_SALARIE</field>'.
                            '<type>eq</type>'.
                            '<value>SMITH</value>'.
                        '</comparator>'.
                    '</operand>'.
                    '<operand>'.
                        '<comparator>'.
                            '<field>ID_PRENOM_SALARIE</field>'.
                            '<type>neq</type>'.
                            '<value>JOHN</value>'.
                        '</comparator>'.
                    '</operand>'.
                '</operator>'
            ,
                array(
                    'codeMetadata' => null,
                    'typeRegle' => 'and',
                    'values' => array(),
                    'operator' => null,
                    'children' => [
                        array (
                            'codeMetadata' => 'ID_NOM_SALARIE',
                            'typeRegle' => null,
                            'values' => array("SMITH"),
                            'operator' => 'eq',
                            'children' => array(),
                        ),
                        array (
                            'codeMetadata' => 'ID_PRENOM_SALARIE',
                            'typeRegle' => null,
                            'values' => array("JOHN"),
                            'operator' => 'neq',
                            'children' => array(),
                        ),
                    ]
                )
            ),
            array(
                '<operator>'.
                    '<type>and</type>'.
                    '<operand>'.
                        '<comparator>'.
                            '<field>ID_NOM_SALARIE</field>'.
                            '<type>between</type>'.
                            '<value>DOE</value>'.
                            '<value>SMITH</value>'.
                        '</comparator>'.
                    '</operand>'.
                '</operator>'
                ,
                array(
                    'codeMetadata' => null,
                    'typeRegle' => 'and',
                    'values' => array(),
                    'operator' => null,
                    'children' => [
                        array (
                            'codeMetadata' => 'ID_NOM_SALARIE',
                            'typeRegle' => null,
                            'values' => array("DOE", "SMITH"),
                            'operator' => 'between',
                            'children' => array(),
                        )
                    ]
                )
            ),
        );
    }
}
