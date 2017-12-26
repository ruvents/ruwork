<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\DependencyInjection;

use Ruwork\RoutingToolsBundle\Twig\RoutingHelpersExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class RuworkRoutingToolsExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->setParameter('ruwork_routing_tools.optional_prefix', $config['optional_prefix']);

        $container->setParameter('ruwork_routing_tools.twig_object_as_parameters', $config['twig']['object_as_parameters']);

        if (!$config['twig']['routing_helpers']) {
            $container->removeDefinition(RoutingHelpersExtension::class);
        }
    }
}
