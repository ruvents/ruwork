<?php

namespace Ruwork\DoctrineFilterBundle\DependencyInjection\Compiler;

use Ruwork\DoctrineFilterBundle\FilterManager;
use Ruwork\DoctrineFilterBundle\Type\FilterTypeInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(FilterManager::class)) {
            return;
        }

        $filterTypes = $container->findTaggedServiceIds($tag = 'ruwork_doctrine_filter_type', true);

        $filterTypeRefs = [];

        foreach ($filterTypes as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!class_implements($class, FilterTypeInterface::class)) {
                throw new \InvalidArgumentException();
            }

            $filterTypeRefs[$class] = new Reference($id);
        }

        $container
            ->getDefinition(FilterManager::class)
            ->setArgument('$types', ServiceLocatorTagPass::register($container, $filterTypeRefs));
    }
}
