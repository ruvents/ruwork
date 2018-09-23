<?php

declare(strict_types=1);

namespace Ruwork\Filter\DependencyInjection;

use Ruwork\Filter\Factory\FilterFactory;
use Ruwork\Filter\Type\FilterTypeInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddFilterTypesPass implements CompilerPassInterface
{
    private $serviceId;
    private $tag;

    public function __construct(string $serviceId = FilterFactory::class, string $tag = 'ruwork_filter.type')
    {
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

            if (!\is_subclass_of($class, FilterTypeInterface::class)) {
                throw new \LogicException(\sprintf('Filter type "%s" must implement "%s".', $class, FilterTypeInterface::class));
            }

            $references[$class] = new Reference($id);
        }

        $container
            ->findDefinition($this->serviceId)
            ->setArgument('$types', ServiceLocatorTagPass::register($container, $references));
    }
}
