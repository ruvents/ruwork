<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Form\FormTypeInterface;

class Configuration implements ConfigurationInterface
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
                    ->end()
                    ->scalarNode('uploads_dir')
                        ->cannotBeEmpty()
                        ->defaultValue('uploads')
                    ->end()
                    ->scalarNode('default_form_type')
                        ->defaultNull()
                        ->validate()
                            ->ifTrue(function ($class) {
                                return !is_subclass_of($class, FormTypeInterface::class);
                            })
                            ->thenInvalid(sprintf('%%s must implement "%s".', FormTypeInterface::class))
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on
    }
}
