<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle\DependencyInjection\Compiler;

use Ruwork\FilterBundle\FilterTypeInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterFilterTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('ruwork_filter.factory')) {
            return;
        }

        $filterTypes = $container->findTaggedServiceIds('ruwork_filter.type', true);
        $filterTypeRefs = [];

        foreach ($filterTypes as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!\is_subclass_of($class, FilterTypeInterface::class)) {
                throw new \InvalidArgumentException(\sprintf('Filter type "%s" must implement "%s".', $class, FilterTypeInterface::class));
            }

            $filterTypeRefs[$class] = new Reference($id);
        }

        $container
            ->findDefinition('ruwork_filter.factory')
            ->setArgument('$types', ServiceLocatorTagPass::register($container, $filterTypeRefs));
    }
}
