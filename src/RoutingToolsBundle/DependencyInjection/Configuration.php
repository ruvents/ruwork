<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\DependencyInjection;

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
                        ->defaultTrue()
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
