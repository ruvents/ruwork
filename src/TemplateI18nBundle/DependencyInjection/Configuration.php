<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\DependencyInjection;

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
            ->root('ruwork_template_i18n')
                ->children()
                    ->arrayNode('naming')
                        ->addDefaultsIfNotSet()
                        ->beforeNormalization()
                            ->ifTrue(function ($value) {
                                return is_array($value) && isset($value['service']) && count($value) > 1;
                            })
                            ->thenInvalid('If "service" is passed, other options are not accepted.')
                        ->end()
                        ->children()
                            ->scalarNode('service')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('locale_suffix_pattern')
                                ->cannotBeEmpty()
                                ->defaultValue('%kernel.default_locale%')
                            ->end()
                            ->scalarNode('extension_pattern')
                                ->cannotBeEmpty()
                                ->defaultValue('\.\w+\.twig')
                            ->end()
                            ->scalarNode('no_suffix_locale')
                                ->cannotBeEmpty()
                                ->defaultValue('%kernel.default_locale%')
                            ->end();
        // @formatter:on

        return $builder;
    }
}
