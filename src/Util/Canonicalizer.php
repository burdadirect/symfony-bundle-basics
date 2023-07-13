<?php

namespace HBM\BasicsBundle\Util;

class Canonicalizer {

  /**
   * @param $string
   *
   * @return false|mixed|string|string[]|null
   */
  public static function canonicalize($string): mixed {
    if (null === $string) {
      return null;
    }

    $encoding = mb_detect_encoding(trim($string));
    return $encoding
      ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
      : mb_convert_case($string, MB_CASE_LOWER);
  }

}
