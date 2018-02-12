<?php

namespace Ruvents\RuworkBundle\DependencyInjection\Compiler;

use Ruvents\RuworkBundle\Twig\Extension\RoutingExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceTwigRoutingExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($name = 'twig.extension.routing')) {
            return;
        }

        $container
            ->findDefinition($name)
            ->setClass(RoutingExtension::class)
            ->setAutowired(true);
    }
}
