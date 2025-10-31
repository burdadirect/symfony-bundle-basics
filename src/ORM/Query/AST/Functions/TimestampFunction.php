<?php

declare(strict_types=1);

namespace HBM\BasicsBundle\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * "TIMESTAMP" "(" ArithmeticPrimary ")"
 * or
 * "TIMESTAMP" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 *
 * @see    www.doctrine-project.org
 */
class TimestampFunction extends FunctionNode
{
    public ?Node $dateOrDateTime = null;

    public ?Node $time = null;

    /**
     * @throws ASTException
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        $sql = 'TIMESTAMP(';
        $sql .= $this->dateOrDateTime->dispatch($sqlWalker);

        if ($this->time) {
            $sql .= ',' . $this->time->dispatch($sqlWalker);
        }
        $sql .= ')';

        return $sql;
    }

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->dateOrDateTime = $parser->ArithmeticPrimary();

        $lexer = $parser->getLexer();

        if ($lexer->isNextToken(TokenType::T_COMMA)) {
            $parser->match(TokenType::T_COMMA);
            $this->time = $parser->ArithmeticPrimary();
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
