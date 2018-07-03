<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Twig\Node;

use Ruwork\FrujaxBundle\EventListener\FrujaxTemplateListener;
use Twig\Compiler;
use Twig\Node\Node;

final class FrujaxNode extends Node
{
    public function __construct(Node $name, Node $body, int $lineno, string $tag)
    {
        parent::__construct(['name' => $name, 'body' => $body], [], $lineno, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("\$frujax = ob_get_flush();\n")
            ->write('$this->env->getRuntime(')
            ->string(FrujaxTemplateListener::class)
            ->raw(')->register(')
            ->subcompile($this->getNode('name'))
            ->write(", \$frujax);\n")
            ->write("unset(\$frujax);\n");
    }
}
