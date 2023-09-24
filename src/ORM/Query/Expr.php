<?php

namespace HBM\BasicsBundle\ORM\Query;

class Expr extends \Doctrine\ORM\Query\Expr
{
    /**
     * Escapes a literal value used in a like query, if necessary, according to the DQL syntax.
     *
     * @param mixed $literal the literal value
     */
    public static function escapeLike($literal): string
    {
        if (\is_numeric($literal) && !\is_string($literal)) {
            return (string) $literal;
        }

        if (\is_bool($literal)) {
            return $literal ? 'true' : 'false';
        }

        return \addcslashes($literal, '%_');
    }

    public static function uniqueIdentifier(string $prefix, string $postfix = null): string
    {
        return $prefix . \str_replace('.', '', \uniqid('', true)) . $postfix;
    }
}
