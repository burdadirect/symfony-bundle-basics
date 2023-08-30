<?php

namespace HBM\BasicsBundle\Util;

class Canonicalizer
{
    /**
     * @return null|false|mixed|string|string[]
     */
    public static function canonicalize($string): mixed
    {
        if ($string === null) {
            return null;
        }

        $encoding = mb_detect_encoding(trim($string));

        return $encoding
          ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
          : mb_convert_case($string, MB_CASE_LOWER);
    }
}
