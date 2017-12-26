<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\DependencyInjection\Compiler;

use Ruwork\RoutingToolsBundle\Twig\BridgeRoutingExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ReplaceBridgeRoutingExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasParameter($parameter = 'ruwork_routing_tools.twig_object_as_parameters')
            && $container->getParameter($parameter)
            && $container->has($service = 'twig.extension.routing')
        ) {
            $container
                ->findDefinition($service)
                ->setClass(BridgeRoutingExtension::class)
                ->setArgument('$router', new Reference('router'))
                ->setArgument('$accessor', new Reference('property_accessor'));
        }
    }
}
