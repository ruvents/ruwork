<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\ORM\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class OperatorFunction extends FunctionNode
{
    const NAME = 'operator';

    /**
     * @var Node
     */
    public $left;

    /**
     * @var string
     */
    public $operator;

    /**
     * @var Node
     */
    public $right;

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_STRING);
        $this->operator = $parser->getLexer()->token['value'];
        $parser->match(Lexer::T_COMMA);
        $this->left = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->right = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return '('.$this->left->dispatch($sqlWalker).' '.$this->operator.' '.$this->right->dispatch($sqlWalker).')';
    }
}
