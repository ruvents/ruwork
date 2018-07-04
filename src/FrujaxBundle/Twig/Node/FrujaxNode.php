<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Twig\Node;

use Ruwork\FrujaxBundle\EventListener\FrujaxPartListener;
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
            ->write("\$frujaxPartContent = ob_get_flush();\n")
            ->write('$this->env->getRuntime(')
            ->string(FrujaxPartListener::class)
            ->raw(')->onPart(')
            ->subcompile($this->getNode('name'))
            ->write(", \$frujaxPartContent);\n")
            ->write("unset(\$frujaxPartContent);\n");
    }
}
