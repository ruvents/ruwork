<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        /* @noinspection PhpIncompatibleReturnTypeInspection */
        // @formatter:off
        return (new TreeBuilder())
            ->root('ruwork_doctrine_behaviors')
                ->beforeNormalization()
                    ->ifTrue(function ($value): bool {
                        return \is_array($value) && !\array_key_exists('profiles', $value);
                    })
                    ->then(function (array $value): array {
                        return [
                            'profiles' => [
                                '*' => $value,
                            ],
                        ];
                    })
                ->end()
                ->children()
                    ->arrayNode('profiles')
                        ->cannotBeEmpty()
                        ->addDefaultChildrenIfNoneSet([
                            'connection' => '*',
                        ])
                        ->useAttributeAsKey('connection')
                        ->arrayPrototype()
                            ->children()
                                ->append($this->fixDefaultSchema())
                                ->append($this->textSearchIndex())
                            ->end()
                        ->end()
                        ->validate()
                            ->always(function (array $value): array {
                                if (isset($value['*']) && 1 < \count($value)) {
                                    throw new \InvalidArgumentException('Global behavior setting (*) cannot be used along with concrete profiles.');
                                }

                                return $value;
                            })
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on
    }

    private function fixDefaultSchema(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('fix_default_schema')
                ->canBeDisabled();
        // @formatter:on
    }

    private function textSearchIndex(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('text_search_index')
                ->canBeDisabled();
        // @formatter:on
    }
}
