<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        return (new TreeBuilder())
            ->root('ruwork_admin')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('debug')
                        ->defaultValue('%kernel.debug%')
                    ->end()
                    ->append($this->forms())
                    ->append($this->list())
                    ->append($this->menu())
                    ->append($this->entities())
                ->end()
            ->end();
    }

    private function menu(string $name = 'menu', bool $withChildren = true): ArrayNodeDefinition
    {
        $definition = (new TreeBuilder())
            ->root($name);

        $prototype = $definition
            ->arrayPrototype()
                ->children()
                    ->append($this->requiresGranted())
                    ->scalarNode('title')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->append($this->attributes())
                    ->scalarNode('url')->end()
                    ->scalarNode('route')->end()
                    ->variableNode('route_params')
                        ->defaultValue([])
                        ->validate()
                            ->ifTrue(function ($value) {
                                return !\is_array($value);
                            })
                            ->thenInvalid('The "attributes" value must be an array, "%s" given.')
                        ->end()
                    ->end()
                    ->scalarNode('active')->defaultNull()->end()
                    ->scalarNode('entity')->end();

        if ($withChildren) {
            $prototype->append($this->menu('children', false));
        }

        return $definition;
    }

    private function forms(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('forms')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('default_theme')
                        ->cannotBeEmpty()
                        ->defaultValue('@RuworkAdmin/forms.html.twig')
                    ->end()
                    ->arrayNode('type_aliases')
                        ->scalarPrototype()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end();
    }

    private function list(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('list')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('types_template')
                        ->cannotBeEmpty()
                        ->defaultValue('@RuworkAdmin/list_field_types.html.twig')
                    ->end()
                ->end();
    }

    private function entities(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('entities')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->append($this->requiresGranted())
                        ->append($this->listAction('List'))
                        ->append($this->formAction('create', 'Create'))
                        ->append($this->formAction('edit', 'Edit'))
                        ->append($this->deleteAction())
                    ->end()
                ->end();
    }

    private function listAction(string $defaultTitle): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('list')
                ->canBeDisabled()
                ->children()
                    ->append($this->requiresGranted())
                    ->scalarNode('title')
                        ->defaultValue($defaultTitle)
                        ->cannotBeEmpty()
                    ->end()
                    ->integerNode('per_page')
                        ->defaultValue(20)
                    ->end()
                    ->append($this->listFields())
                ->end();
    }

    private function listFields(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('fields')
                ->defaultValue([
                    ['property_path' => null, 'type' => 'id', 'title' => null],
                    ['property_path' => null, 'type' => 'title', 'title' => null],
                    ['property_path' => null, 'type' => 'actions', 'title' => null],
                ])
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('property_path')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('type')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('title')
                            ->defaultNull()
                        ->end()
                    ->end()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($value) {
                            if (!\preg_match('/^(?<property_path>[\[\]\.\w-]+)?(?>\@(?<type>[\w-\\\]+))?(?>\{(?<title>.*)\})?$/', $value, $matches)) {
                                throw new \InvalidArgumentException(\sprintf('"%s" is not a valid field definition.', $value));
                            }

                            $matches = \array_filter($matches);

                            $propertyPath = $matches['property_path'] ?? null;
                            $type = $matches['type'] ?? null;

                            if (null === $propertyPath && null === $type) {
                                throw new \LogicException('"property_path" and "type" cannot be both empty.');
                            }

                            return [
                                'property_path' => $propertyPath,
                                'type' => $type,
                                'title' => $matches['title'] ?? null,
                            ];
                        })
                    ->end()
                ->end();
    }

    private function formAction(string $name, string $defaultTitle): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root($name)
                ->canBeDisabled()
                ->children()
                    ->append($this->requiresGranted())
                    ->scalarNode('title')
                        ->defaultValue($defaultTitle)
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('type')
                        ->defaultNull()
                    ->end()
                    ->variableNode('options')
                        ->defaultValue([])
                    ->end()
                    ->scalarNode('theme')
                        ->defaultNull()
                    ->end()
                    ->append($this->formFields())
                ->end();
    }

    private function deleteAction(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('delete')
                ->canBeDisabled()
                ->append($this->requiresGranted());
    }

    private function formFields(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('fields')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('type')
                            ->defaultNull()
                        ->end()
                        ->variableNode('options')
                            ->defaultValue([])
                        ->end()
                        ->append($this->requiresGranted())
                    ->end()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($value) {
                            static $groupI = 1;

                            if (!\preg_match('/^(?<property_path>[\[\]\.\w-]+)?(?>\@(?<type>[\w-\\\]+))?(?<attr_class>(?>\.[\w-]+)+)?(?>\{(?<label>.*)\})?$/', $value, $matches)) {
                                throw new \InvalidArgumentException(\sprintf('"%s" is not a valid field definition.', $value));
                            }

                            $matches = \array_filter($matches);

                            $propertyPath = $matches['property_path'] ?? null;
                            $type = $matches['type'] ?? null;

                            if (!$propertyPath) {
                                if ('group' === $type) {
                                    $name = '__group'.($groupI++);
                                } else {
                                    throw new \InvalidArgumentException(\sprintf('"%s" is not a valid field definition. Not specifying property is allowed only for the "group" type.', $value));
                                }
                            } else {
                                $name = \preg_replace('/[\[\]\.]+/', '_', $propertyPath);
                            }

                            return [
                                'name' => $name,
                                'type' => $type,
                                'options' => [
                                    'mapped' => 'group' !== $type,
                                    'property_path' => $propertyPath,
                                    'label' => $matches['label'] ?? null,
                                    'attr' => [
                                        'class' => \strtr($matches['attr_class'] ?? '', '.', ' '),
                                    ],
                                ],
                            ];
                        })
                    ->end()
                ->end();
    }

    private function requiresGranted(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('requires_granted')
                ->scalarPrototype()
                    ->cannotBeEmpty()
                ->end();
    }

    private function attributes(): VariableNodeDefinition
    {
        /** @var VariableNodeDefinition $definition */
        $definition = (new TreeBuilder())
            ->root('attributes', 'variable')
                ->defaultValue([])
                ->validate()
                    ->ifTrue(function ($value) {
                        return !\is_array($value);
                    })
                    ->thenInvalid('The "attributes" value must be an array, "%s" given.')
                ->end();

        return $definition;
    }
}
