<?php

namespace ApiBundle\PopulationFilter;

class SimpleXmlParser
{
    /**
     * Parse une chaine XML
     *
     * @param string     $xml
     * @param XmlVisitor $visitor
     */
    public function parse($xml, XmlVisitor $visitor)
    {
        $doc = new \SimpleXMLIterator('<root>'.$xml.'</root>');

        $this->parseNode($doc, $visitor);
    }

    /**
     * Parse un noeud XML
     *
     * @param \SimpleXMLIterator $doc
     * @param XmlVisitor         $visitor
     */
    protected function parseNode(\SimpleXMLIterator $doc, XmlVisitor $visitor)
    {
        if (isset($doc->comparator)) {
            $this->parseComparator($doc->comparator, $visitor);
        }

        if (isset($doc->operator)) {
            $this->parseOperator($doc->operator, $visitor);
        }

        if (isset($doc->type_individuel)) {
            $visitor->visitTypeIndividuelNode((string) $doc->type_individuel);
        }
    }

    /**
     * Parse un noeud comparator
     *
     * @param \SimpleXMLIterator $comparator
     * @param XmlVisitor $visitor
     */
    protected function parseComparator(\SimpleXMLIterator $comparator, XmlVisitor $visitor)
    {
        $values = array();
        if ($comparator->value instanceof \SimpleXMLIterator) {
            foreach ($comparator->value as $value) {
                $values[] = (string) $value;
            }
        } else {
            $values[] = $comparator->value;
        }

        $visitor->visitComparatorNode(
            (string) $comparator->field,
            (string) $comparator->type,
            $values
        );
    }

    /**
     * Parse un noeud operator
     *
     * @param \SimpleXMLIterator $operator
     * @param XmlVisitor $visitor
     */
    protected function parseOperator(\SimpleXMLIterator $operator, XmlVisitor $visitor)
    {
        if (isset($operator->operand)) {
            $type = (string) $operator->type;

            if (count($operator->operand) >= 2 || $type == 'not') {
                $visitor->visitOperatorNode($type);
                $visitor->beforeOperandNode($type);
                $num = 0;
                foreach ($operator->operand as $operand) {
                    if ($num++ > 0) {
                        $visitor->betweenOperandNode($type);
                    };
                    $this->parseNode($operand, $visitor);
                }
                $visitor->afterOperandNode($type);
            } else {
                $this->parseNode($operator->operand, $visitor);
            }
        }
    }
}
