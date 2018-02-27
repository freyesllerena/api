<?php

namespace ApiBundle\Manager;

class PopulationFilterManager
{

    /**
     * Deserialise un filtre par population au format xml
     *
     * @param  string $xml
     * @return array
     */
    public function unserialize($xml)
    {

        if (!$xml) {
            return array();
        }

        if (!$xml instanceof \SimpleXMLElement) {
            $xml = new \SimpleXMLElement('<root>'.$xml.'</root>');
        }

        if (isset($xml->operator)) {
            $operator = $xml->operator;
            $data = array(
                'codeMetadata' => null,
                'typeRegle' => (string) $operator->type,
                'values' => array(),
                'operator' => null,
                'children' => array(),
            );

            if ($operator->operand->count() > 0) {
                foreach ($operator->operand as $operand) {
                    if ($operand->children()) {
                        $data['children'][] = $this->unserialize($operand);
                    }
                }
            }

            return $data;
        } elseif (isset($xml->comparator)) {
            $comparator = $xml->comparator;
            $data = array(
                'codeMetadata' => (string) $comparator->field,
                'typeRegle' => null,
                'values' => array(),
                'operator' => (string) $comparator->type,
                'children' => array(),
            );

            foreach ($comparator->value as $value) {
                $data['values'][] = (string) $value;
            }

            return $data;
        }
    }

    /**
     * Serialise un filtre par population au format xml
     *
     * @param array $data
     * @return string
     */
    public function serialize(array $data)
    {
        $data += array('typeRegle' => null, 'operator' => null);

        $data['typeRegle'] = strtolower($data['typeRegle']);
        if (in_array($data['typeRegle'], array('and', 'or', 'not'))) {
            $xml = '<operator>'.
                '<type>'.$data['typeRegle'].'</type>';

            if ($data['children']) {
                foreach ($data['children'] as $child) {
                    $xml .= '<operand>'.$this->serialize($child).'</operand>';
                }
            } else {
                $xml .= '<operand></operand>';
            }

            $xml.='</operator>';

            return $xml;
        } elseif ($data['operator']) {
            $xml = '<comparator>'.
                '<field>'.$data['codeMetadata'].'</field>'.
                '<type>'.$data['operator'].'</type>';

            if ($data['values']) {
                foreach ($data['values'] as $value) {
                    if (isset($value) && isset($value['code'])) {
                        $value = $value['code'];
                    }
                    $xml .= '<value>' . $value . '</value>';
                }
            } else {
                $xml .= '<value></value>';
            }

            $xml .= '</comparator>';
            return $xml;
        }

        return '';
    }
}
