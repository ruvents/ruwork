<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\ORM\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ToTsvectorFunction extends FunctionNode
{
    /**
     * @var Node
     */
    public $config;

    /**
     * @var Node
     */
    public $document;

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser): void
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        if (Lexer::T_COMMA === $lexer->glimpse()['type']) {
            $this->config = $parser->StringPrimary();
            $parser->getLexer()->moveNext();
        }

        $this->document = $parser->StateFieldPathExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return \sprintf('to_tsvector(%s%s)',
            $this->config ? $this->config->dispatch($sqlWalker).', ' : '',
            $this->document->dispatch($sqlWalker)
        );
    }
}
