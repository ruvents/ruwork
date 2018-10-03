<?php

declare(strict_types=1);

namespace Ruwork\ReminderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        // @formatter:off
        $builder
            ->root('ruwork_reminder')
                ->children()
                    ->arrayNode('marker')
                        ->children()
                            ->arrayNode('database')
                                ->children()
                                    ->scalarNode('table')->defaultValue('reminder_marker')->end()
                                    ->scalarNode('connection')->defaultValue('default')->end()
                                ->end()
                            ->end()
                            ->scalarNode('service')->cannotBeEmpty()->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function (array $value): bool {
                                return count($value) > 1;
                            })
                            ->thenInvalid('You cannot set multiple reminder markers.')
                        ->end()
                    ->end()
                ->end();
        // @formatter:on

        return $builder;
    }
}
