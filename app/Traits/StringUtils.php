<?php

namespace App\Traits;

trait StringUtils
{
    public function snakeToCamelCase($string, $capitalizeFirstCharacter = false): string
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }
}
