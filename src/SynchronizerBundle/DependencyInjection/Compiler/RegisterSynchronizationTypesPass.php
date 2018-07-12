<?php

declare(strict_types=1);

namespace Ruwork\SynchronizerBundle\DependencyInjection\Compiler;

use Ruwork\Synchronizer\Type\TypeInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterSynchronizationTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('ruwork_synchronizer.factory')) {
            return;
        }

        $syncTypes = $container->findTaggedServiceIds('ruwork_synchronizer.synchronization_type', true);
        $syncTypeRefs = [];

        foreach ($syncTypes as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!\is_subclass_of($class, TypeInterface::class)) {
                throw new \InvalidArgumentException(\sprintf('Synchronization type "%s" must implement "%s".', $class, TypeInterface::class));
            }

            $syncTypeRefs[$class] = new Reference($id);
        }

        $container
            ->findDefinition('ruwork_synchronizer.factory')
            ->setArgument('$types', ServiceLocatorTagPass::register($container, $syncTypeRefs));
    }
}
