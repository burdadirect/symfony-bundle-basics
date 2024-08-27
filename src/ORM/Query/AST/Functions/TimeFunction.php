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
 * "TIME" "(" ArithmeticPrimary ")"
 */
class TimeFunction extends FunctionNode {

  public ?Node $date = null;

  /**
   * @inheritdoc
   *
   * @throws ASTException
   */
  public function getSql(SqlWalker $sqlWalker): string {
    return 'TIME('.$this->date->dispatch($sqlWalker).')';
  }

  /**
   * @inheritdoc
   *
   * @throws QueryException
   */
  public function parse(Parser $parser): void {
    $parser->match(Lexer::T_IDENTIFIER);
    $parser->match(Lexer::T_OPEN_PARENTHESIS);

    $this->date = $parser->ArithmeticPrimary();

    $parser->match(Lexer::T_CLOSE_PARENTHESIS);
  }
}
