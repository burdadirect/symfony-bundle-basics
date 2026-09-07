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
 * "DATE_FORMAT" "(" ArithmeticExpression ", " StringPrimary ")"
 */
class DateFormatFunction extends FunctionNode
{
    public ?Node $dateExpression = null;

    public ?Node $patternExpression = null;

    /**
     * @throws ASTException
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'DATE_FORMAT(' .
          $this->dateExpression->dispatch($sqlWalker) . ', ' .
          $this->patternExpression->dispatch($sqlWalker) .
          ')';
    }

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticExpression();
        $parser->match(TokenType::T_COMMA);
        $this->patternExpression = $parser->StringPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

}
