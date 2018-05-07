<?php

declare(strict_types=1);

namespace Ruwork\AnnotationTools\Factory;

use Doctrine\Common\Annotations\Reader;
use Ruwork\AnnotationTools\Mapping\MappingInterface;
use Ruwork\AnnotationTools\Metadata\ClassMetadata;
use Ruwork\AnnotationTools\Metadata\MethodMetadata;
use Ruwork\AnnotationTools\Metadata\PropertyMetadata;

abstract class AbstractMetadataFactory implements MetadataFactoryInterface
{
    protected const TARGET_CLASS = 0;
    protected const TARGET_PROPERTY = 1;
    protected const TARGET_METHOD = 2;

    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    final public function getMetadata(string $class): ClassMetadata
    {
        $targetsMap = array_flip($this->getTargets());
        $reflectionClass = new \ReflectionClass($class);
        $classMappings = [];
        $properties = [];
        $methods = [];

        if (isset($targetsMap[self::TARGET_CLASS])) {
            $classMappings = iterator_to_array($this->getClassMappings($reflectionClass));
        }

        if (isset($targetsMap[self::TARGET_PROPERTY])) {
            foreach ($reflectionClass->getProperties() as $reflectionProperty) {
                $name = $reflectionProperty->getName();
                $mappings = iterator_to_array($this->getPropertyMappings($reflectionProperty));
                $properties[$name] = new PropertyMetadata($name, $mappings);
            }
        }

        if (isset($targetsMap[self::TARGET_METHOD])) {
            foreach ($reflectionClass->getMethods() as $reflectionMethod) {
                $name = $reflectionMethod->getName();
                $mappings = iterator_to_array($this->getMethodMappings($reflectionMethod));
                $methods[$name] = new MethodMetadata($name, $mappings);
            }
        }

        return new ClassMetadata($class, $classMappings, $properties, $methods);
    }

    abstract protected function getTargets(): array;

    abstract protected function supports($mapping, int $target): bool;

    private function getClassMappings(\ReflectionClass $reflectionClass): \Generator
    {
        foreach ($this->reader->getClassAnnotations($reflectionClass) as $annotation) {
            if ($annotation instanceof MappingInterface &&
                $this->supports($annotation, self::TARGET_CLASS)
            ) {
                yield $annotation::getName() => $annotation;
            }
        }
    }

    private function getPropertyMappings(\ReflectionProperty $reflectionProperty): \Generator
    {
        foreach ($this->reader->getPropertyAnnotations($reflectionProperty) as $annotation) {
            if ($annotation instanceof MappingInterface &&
                $this->supports($annotation, self::TARGET_PROPERTY)
            ) {
                yield $annotation::getName() => $annotation;
            }
        }
    }

    private function getMethodMappings(\ReflectionMethod $reflectionMethod): \Generator
    {
        foreach ($this->reader->getMethodAnnotations($reflectionMethod) as $annotation) {
            if ($annotation instanceof MappingInterface &&
                $this->supports($annotation, self::TARGET_METHOD)
            ) {
                yield $annotation::getName() => $annotation;
            }
        }
    }
}
