<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\DependencyInjection;

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
            ->root('ruvents_ruwork')
                ->children()
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
        // @formatter:on

        return $builder;
    }
}
