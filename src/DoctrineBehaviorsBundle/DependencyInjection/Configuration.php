<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DependencyInjection;

use Ruwork\DoctrineBehaviorsBundle\Strategy\AuthorStrategy\SecurityTokenAuthorStrategy;
use Ruwork\DoctrineBehaviorsBundle\Strategy\TimestampStrategy\FieldTypeTimestampStrategy;
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
                        return is_array($value) && !array_key_exists('profiles', $value);
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
                            ->append($this->searchColumn())
                            ->append($this->author())
                            ->append($this->multilingual())
                            ->append($this->timestamp('persist_timestamp'))
                            ->append($this->timestamp('update_timestamp'))
                        ->end()
                        ->validate()
                            ->always(function (array $value) {
                                if (isset($value['*']) && 1 < count($value)) {
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

    private function searchColumn(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('search_column')
                ->canBeDisabled();
        // @formatter:on
    }

    private function author(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('author')
                ->canBeDisabled()
                ->children()
                    ->scalarNode('strategy')
                        ->cannotBeEmpty()
                        ->defaultValue(SecurityTokenAuthorStrategy::class)
                    ->end()
                    ->append($this->defaultMapping()
                        ->append($this->column()->canBeEnabled())
                        ->append($this->manyToOne()->canBeEnabled())
                    )
                ->end();
        // @formatter:on
    }

    private function multilingual(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('multilingual')
                ->canBeDisabled()
                ->children()
                    ->scalarNode('default_locale')
                        ->cannotBeEmpty()
                        ->defaultValue('%kernel.default_locale%')
                    ->end()
                    ->append($this->defaultMapping()
                        ->append($this->embedded()->canBeEnabled())
                        ->append($this->oneToOne()->canBeEnabled())
                    )
                ->end();
        // @formatter:on
    }

    private function timestamp(string $name): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root($name)
                ->canBeDisabled()
                ->children()
                    ->scalarNode('strategy')
                        ->cannotBeEmpty()
                        ->defaultValue(FieldTypeTimestampStrategy::class)
                    ->end()
                    ->append($this->defaultMapping()
                        ->append($this->column())
                    )
                ->end();
        // @formatter:on
    }

    private function defaultMapping()
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('default_mapping')
                ->canBeEnabled()
                ->validate()
                    ->always(function (array $value): array {
                        $variants = [];
                        $enabledVariants = [];

                        foreach ($value as $variant => $mapping) {
                            if (is_array($mapping)) {
                                if ($mapping['enabled'] ?? true) {
                                    $enabledVariants[] = $variant;
                                }

                                $variants[] = $variant;
                            }
                        }

                        if (1 !== count($enabledVariants)) {
                            throw new \InvalidArgumentException(sprintf('Exactly one of the mapping variants among "%s" must be enabled.', implode('", "', $variants)));
                        }

                        return $value + ['enabled_variant' => $enabledVariants[0]];
                    })
                ->end();
        // @formatter:on
    }

    private function column(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('column')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('type')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->integerNode('length')
                        ->min(1)
                        ->defaultNull()
                    ->end()
                    ->booleanNode('unique')
                        ->defaultFalse()
                    ->end()
                    ->booleanNode('nullable')
                        ->defaultFalse()
                    ->end()
                ->end();
        // @formatter:on
    }

    private function oneToOne(): ArrayNodeDefinition
    {
        // @formatter:off
        return $this->association('one_to_one')
            ->children()
                ->booleanNode('orphan_removal')
                    ->defaultFalse()
                ->end()
            ->end();
        // @formatter:on
    }

    private function manyToOne(): ArrayNodeDefinition
    {
        return $this->association('many_to_one');
    }

    private function association(string $name): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root($name)
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('target_entity')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->variableNode('cascade')
                        ->defaultNull()
                    ->end()
                    ->enumNode('fetch')
                        ->values(['LAZY', 'EAGER', 'EXTRA_LAZY'])
                        ->cannotBeEmpty()
                        ->defaultValue('LAZY')
                        ->beforeNormalization()
                            ->always(function ($value) {
                                return is_string($value) ? strtoupper($value) : $value;
                            })
                        ->end()
                    ->end()
                    ->booleanNode('nullable')
                        ->defaultTrue()
                    ->end()
                ->end();
        // @formatter:on
    }

    private function embedded(): ArrayNodeDefinition
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('embedded')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('class')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('column_prefix')
                        ->defaultNull()
                    ->end()
                ->end();
        // @formatter:on
    }
}
