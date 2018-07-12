<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\DependencyInjection;

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
            ->root('ruwork_runet_id')
                ->beforeNormalization()
                    ->ifTrue(function ($value): bool {
                        return \is_array($value) && [] !== $value && !\array_key_exists('clients', $value);
                    })
                    ->then(function (array $value): array {
                        return [
                            'clients' => [
                                'default' => $value,
                            ],
                            'default_client' => 'default',
                        ];
                    })
                ->end()
                ->children()
                    ->arrayNode('clients')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->useAttributeAsKey('name')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('key')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('secret')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('api_uri')
                                    ->cannotBeEmpty()
                                    ->defaultNull()
                                ->end()
                                ->scalarNode('oauth_uri')
                                    ->cannotBeEmpty()
                                    ->defaultNull()
                                ->end()
                                ->arrayNode('plugins')
                                    ->scalarPrototype()
                                        ->cannotBeEmpty()
                                    ->end()
                                ->end()
                                ->scalarNode('http_client')
                                    ->defaultNull()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->scalarNode('default_client')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
                ->validate()
                    ->ifTrue(function ($value): bool {
                        return \is_array($value)
                            && isset($value['default_client'])
                            && isset($value['clients'])
                            && !isset($value['clients'][$value['default_client']]);
                    })
                    ->then(function (array $value): void {
                        throw new \InvalidArgumentException(\sprintf('Client "%s" is not defined and cannot be used as default.', $value['default_client']));
                    })
                ->end();
        // @formatter:on

        return $builder;
    }
}
