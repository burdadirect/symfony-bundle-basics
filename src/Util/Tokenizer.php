<?php

namespace HBM\BasicsBundle\Util;

class Tokenizer
{
    public static function tokenize(?string $string, string $separator, bool $trimEntries = true, bool $trimEmptyEntries = true): array
    {
        if ($string === null) {
            return [];
        }

        $array = explode($separator, $string);
        if ($trimEntries) {
            $array = self::trimValuesInArray($array);
        }
        if ($trimEmptyEntries) {
            $array = self::trimEmptyValuesFromArray($array);
        }

        return $array;
    }

    protected static function trimValuesInArray(array $array, string $characters = " \n\r\t\v\x00"): array {
        return array_map(['trim', [$characters]], $array);
    }

    protected static function trimEmptyValuesFromArray(array $array, array $emptyValues = ['', null], bool $trimEntries = true): array {
        if ($trimEntries) {
            $array = self::trimValuesInArray($array);
        }
        return array_diff($array, $emptyValues);
    }
}
