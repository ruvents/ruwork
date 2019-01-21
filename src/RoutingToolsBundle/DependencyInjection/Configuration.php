<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\DependencyInjection;

use Ruwork\RouteOptionalPrefix\RouterDecorator;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();

        // @formatter:off
        $builder
            ->root('ruwork_routing_tools')
                ->children()
                    ->booleanNode('optional_prefix')
                        ->defaultFalse()
                        ->validate()
                            ->ifTrue(function (bool $value): bool {
                                return $value && !class_exists(RouterDecorator::class);
                            })
                            ->thenInvalid('ruwork/route-optional-prefix package is required to enable optional prefix.')
                        ->end()
                    ->end()
                    ->arrayNode('twig')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('object_as_parameters')
                                ->defaultValue(class_exists(RoutingExtension::class))
                                ->validate()
                                    ->ifTrue(function ($value) {
                                        return $value && !class_exists(RoutingExtension::class);
                                    })
                                    ->thenInvalid('Twig bridge is not installed.')
                                ->end()
                            ->end()
                            ->booleanNode('routing_helpers')
                                ->defaultTrue()
                            ->end()
                        ->end()
                    ->end();
        // @formatter:on

        return $builder;
    }
}
