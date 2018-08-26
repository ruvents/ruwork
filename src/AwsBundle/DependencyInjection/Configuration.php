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
                ->children()
                    ->scalarNode('http_handler')
                        ->defaultValue(\interface_exists(HttpClient::class) ? HttplugHandler::class : null)
                    ->end()
                ->end()
                ->ignoreExtraKeys(false)
            ->end();
        // @formatter:on
    }
}
