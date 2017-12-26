<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\DependencyInjection\Compiler;

use Ruwork\RouteOptionalPrefix\LoaderDecorator;
use Ruwork\RouteOptionalPrefix\RouterDecorator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouteOptionalPrefixPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(LoaderDecorator::class) || !$container->has(RouterDecorator::class)) {
            return;
        }

        if (!$container->hasParameter($parameter = 'ruwork_routing_tools.optional_prefix')
            || !$container->getParameter($parameter)
            || !$container->has('routing.loader')
            || !$container->has('router')
        ) {
            $container->removeDefinition(LoaderDecorator::class);
            $container->removeDefinition(RouterDecorator::class);
        }
    }
}
