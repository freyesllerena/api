<?php

namespace ApiBundle\Helper;

/**
 * Class Tools
 * Cette classe contient des utils - fonctions très génériques.
 *
 * @package ApiBundle\Helper
 */
class Tools
{
    /**
     * Unique Multi-dimension Array
     * @param $array
     * @param $keyField
     * @return array
     */
    public static function uniqueMultidimArray($array, $keyField)
    {
        $tempArray = [];
        $keyArray = [];
        foreach ($array as $key => $val) {
            if (!in_array($val[$keyField], $keyArray)) {
                $keyArray[$key] = $val[$keyField];
                $tempArray[$key] = $val;
            }
        }

        return $tempArray;
    }
}
