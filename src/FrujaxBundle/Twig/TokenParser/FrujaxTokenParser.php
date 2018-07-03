<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Twig\TokenParser;

use Ruwork\FrujaxBundle\Twig\Node\FrujaxNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

final class FrujaxTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $this->parser->getExpressionParser()->parseExpression();
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new FrujaxNode($name, $body, $lineno, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'frujax';
    }

    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endfrujax');
    }
}
