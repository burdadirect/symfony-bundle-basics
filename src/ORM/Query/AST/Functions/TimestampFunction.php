<?php

declare(strict_types=1);

namespace HBM\BasicsBundle\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * "TIMESTAMP" "(" ArithmeticPrimary ")"
 * or
 * "TIMESTAMP" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 *
 * @link    www.doctrine-project.org
 */
class TimestampFunction extends FunctionNode {

  public ?Node $dateOrDateTime = null;

  public ?Node $time = null;

  /**
   * @inheritdoc
   * @throws ASTException
   */
  public function getSql(SqlWalker $sqlWalker): string {
    $sql = 'TIMESTAMP(';
    $sql .= $this->dateOrDateTime->dispatch($sqlWalker);
    if ($this->time) {
      $sql .= ','.$this->time->dispatch($sqlWalker);
    }
    $sql .= ')';

    return $sql;
  }

  /**
   * @inheritdoc
   *
   * @throws QueryException
   */
  public function parse(Parser $parser): void {
    $parser->match(Lexer::T_IDENTIFIER);
    $parser->match(Lexer::T_OPEN_PARENTHESIS);

    $this->dateOrDateTime = $parser->ArithmeticPrimary();

    $lexer = $parser->getLexer();
    if ($lexer->isNextToken(Lexer::T_COMMA)) {
      $parser->match(Lexer::T_COMMA);
      $this->time = $parser->ArithmeticPrimary();
    }

    $parser->match(Lexer::T_CLOSE_PARENTHESIS);
  }
}
