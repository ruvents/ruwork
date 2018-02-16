<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();

        // @formatter:off
        $builder
            ->root('ruwork_reform')
                ->children()
                    ->arrayNode('extensions')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('novalidate')
                                ->defaultTrue()
                            ->end()
                            ->booleanNode('default_datetime_immutable')
                                ->defaultTrue()
                            ->end();
        // @formatter:on

        return $builder;
    }
}
