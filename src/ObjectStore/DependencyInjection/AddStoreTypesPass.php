<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\DependencyInjection;

use Ruwork\ObjectStore\Exception\LogicException;
use Ruwork\ObjectStore\Type\StoreTypeInterface;
use Ruwork\ObjectStore\TypeResolver\StoreTypeResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddStoreTypesPass implements CompilerPassInterface
{
    private $serviceId;
    private $tag;

    public function __construct(
        string $serviceId = StoreTypeResolver::class,
        string $tag = 'ruwork_object_store.type'
    ) {
        $this->serviceId = $serviceId;
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->serviceId)) {
            return;
        }

        $tagged = $container->findTaggedServiceIds($this->tag, true);
        $references = [];

        foreach ($tagged as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!\is_subclass_of($class, StoreTypeInterface::class)) {
                throw new LogicException(\sprintf(
                    'Service tagged with "%s" must implement "%s".',
                    $this->tag,
                    StoreTypeInterface::class
                ));
            }

            $references[$class] = new Reference($id);
        }

        $container
            ->findDefinition($this->serviceId)
            ->setArgument('$types', ServiceLocatorTagPass::register($container, $references));
    }
}
