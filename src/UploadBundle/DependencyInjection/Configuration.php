<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection;

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
            ->root('ruwork_upload')
                ->children()
                    ->scalarNode('public_dir')
                        ->cannotBeEmpty()
                        ->defaultValue('%kernel.project_dir%/public')
                        ->validate()
                            ->always(function ($value) {
                                return \rtrim($value, '/');
                            })
                        ->end()
                    ->end()
                    ->scalarNode('uploads_dir')
                        ->cannotBeEmpty()
                        ->defaultValue('uploads')
                        ->validate()
                            ->always(function ($value) {
                                return \trim($value, '/');
                            })
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on
    }
}
