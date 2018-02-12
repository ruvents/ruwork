<?php

namespace Ruvents\RuworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        return (new TreeBuilder())
            ->root('ruvents_ruwork')
                ->children()
                    ->arrayNode('assets')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('web_dir')
                                ->defaultValue('%kernel.project_dir%/public')
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('i18n')
                        ->canBeEnabled()
                        ->children()
                            ->arrayNode('locales')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->scalarPrototype()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                            ->scalarNode('default_locale')->isRequired()->cannotBeEmpty()->end()
                            ->booleanNode('suffix_controller_templates')->defaultTrue()->end()
                        ->end()
                    ->end()
                    ->arrayNode('mailer')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('users')
                                ->useAttributeAsKey('id')
                                ->normalizeKeys(false)
                                ->validate()
                                    ->always(function (array $users) {
                                        foreach ($users as $name => $user) {
                                            if (!is_string($name)) {
                                                throw new \InvalidArgumentException(
                                                    sprintf('"%s" is not a valid id. Must be string.', $name)
                                                );
                                            }
                                        }

                                        return $users;
                                    })
                                ->end()
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('email')->isRequired()->cannotBeEmpty()->end()
                                        ->arrayNode('name')
                                            ->isRequired()
                                            ->scalarPrototype()
                                                ->cannotBeEmpty()
                                            ->end()
                                            ->beforeNormalization()->castToArray()->end()
                                        ->end()
                                        ->scalarNode('locale')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
