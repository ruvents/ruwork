<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\DependencyInjection\Compiler;

use Ruwork\AdminBundle\ListField\TypeContextProcessor\TypeContextProcessorInterface;
use Ruwork\AdminBundle\Twig\ListExtension;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ListFieldTypeContextProcessorsPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ListExtension::class)) {
            return;
        }

        $processorReferences = $this->findAndSortTaggedServices($tag = 'ruwork_admin.list_field_type_context_processor', $container);
        $processorReferencesByType = [];

        foreach ($processorReferences as $processorReference) {
            $processor = $container->findDefinition((string) $processorReference);
            $class = $processor->getClass();

            if (!is_subclass_of($class, TypeContextProcessorInterface::class)) {
                throw new \InvalidArgumentException(sprintf('Service tagged with "%s" must implement "%s".', $tag, TypeContextProcessorInterface::class));
            }

            $type = \call_user_func([$class, 'getType']);

            $classFile = (new \ReflectionClass($class))->getFileName();
            $container->addResource(new FileResource($classFile));

            $processorReferencesByType[$type][] = $processorReference;
        }

        $processors = array_map(function (array $references) use ($container) {
            return new IteratorArgument($references);
        }, $processorReferencesByType);

        $container->findDefinition(ListExtension::class)
            ->setArgument('$processors', $processors);
    }
}
