<?php

namespace HBM\BasicsBundle\ORM\Query;

use function is_bool;
use function is_numeric;
use function is_string;
use function addcslashes;
use function str_replace;
use function uniqid;

class Expr extends \Doctrine\ORM\Query\Expr {

  /**
   * Escapes a literal value used in a like query, if necessary, according to the DQL syntax.
   *
   * @param mixed $literal The literal value.
   *
   * @return string
   */
  public static function escapeLike($literal): string {
    if (is_numeric($literal) && !is_string($literal)) {
      return (string) $literal;
    }
    if (is_bool($literal)) {
      return $literal ? 'true' : 'false';
    }

    return addcslashes($literal, '%_');
  }

  /**
   * @param string $prefix
   * @param string|null $postfix
   *
   * @return string
   */
  public static function uniqueIdentifier(string $prefix, string $postfix = NULL) : string {
    return $prefix.str_replace('.', '', uniqid('', TRUE)).$postfix;
  }

}
