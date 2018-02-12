<?php

namespace Ruvents\RuworkBundle;

use Ruvents\RuworkBundle\DependencyInjection\Compiler\ReplaceTwigAppVariablePass;
use Ruvents\RuworkBundle\DependencyInjection\Compiler\ReplaceTwigRoutingExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuventsRuworkBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new ReplaceTwigAppVariablePass())
            ->addCompilerPass(new ReplaceTwigRoutingExtensionPass());
    }
}
