<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('ruwork_locale_prefix')
                ->children()
                    ->arrayNode('locales')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->scalarPrototype()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                    ->scalarNode('default_locale')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
                ->validate()
                    ->ifTrue(function (array $value) {
                        return !in_array($value['default_locale'], $value['locales'], true);
                    })
                    ->thenInvalid('Default locale must be one of the specified locales.')
                ->end()
            ->end();
        // @formatter:on
    }
}
