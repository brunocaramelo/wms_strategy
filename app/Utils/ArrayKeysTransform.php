<?php

namespace App\Utils;


class ArrayKeysTransform
{

    public function transformUndescoreToCamelCase(array $array) : array
    {
        $keys = array_map(function ($i) use (&$array) {
            if (is_array($array[$i])) {
                $array[$i] = $this->transformUndescoreToCamelCase($array[$i]);
            }

            $parts = explode('_', $i);

            return array_shift($parts) . implode('', array_map('ucfirst', $parts));

        }, array_keys($array));

        return array_combine($keys, $array);
    }

    public function transformCamelCaseToUndescore(array $array) : array
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            $newKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
            $newArray[$newKey] = is_array($value) ? $this->transformCamelCaseToUndescore($value) : $value;
        }

        return $newArray;
    }

}
