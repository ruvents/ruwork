<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\DependencyInjection;

use Http\Client\HttpClient;
use Ruwork\AwsBundle\HttpHandler\HttplugHandler;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        // @formatter:off
        return (new TreeBuilder())
            ->root('ruwork_aws')
                ->beforeNormalization()
                    ->ifTrue(function ($value): bool {
                        return \is_array($value) && [] !== $value && !\array_key_exists('sdks', $value);
                    })
                    ->then(function (array $value): array {
                        return [
                            'sdks' => [
                                'default' => $value,
                            ],
                            'default_sdk' => 'default',
                        ];
                    })
                ->end()
                ->children()
                    ->arrayNode('sdks')
                        ->isRequired()
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('http_handler')
                                    ->defaultValue(\interface_exists(HttpClient::class) ? HttplugHandler::class : null)
                                ->end()
                            ->end()
                            ->ignoreExtraKeys(false)
                        ->end()
                    ->end()
                    ->scalarNode('default_sdk')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
                ->validate()
                    ->ifTrue(function ($value): bool {
                        return \is_array($value)
                            && isset($value['default_sdk'])
                            && isset($value['sdks'])
                            && !isset($value['sdks'][$value['default_sdk']]);
                    })
                    ->then(function (array $value): void {
                        throw new \InvalidArgumentException(\sprintf('SDK "%s" is not defined and cannot be used as default.', $value['default_sdk']));
                    })
                ->end()
        ->end();
        // @formatter:on
    }
}
