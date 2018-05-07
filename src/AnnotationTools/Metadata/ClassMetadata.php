<?php

declare(strict_types=1);

namespace Ruwork\AnnotationTools\Metadata;

use Ruwork\AnnotationTools\Mapping\MappingInterface;

final class ClassMetadata
{
    use MetadataTrait;

    private $className;
    private $properties;
    private $methods;

    /**
     * @param string             $className
     * @param array              $mappings
     * @param PropertyMetadata[] $properties
     * @param MethodMetadata[]   $methods
     */
    public function __construct(string $className, array $mappings, array $properties, array $methods)
    {
        $this->className = $className;
        $this->mappings = $mappings;
        $this->properties = $properties;
        $this->methods = $methods;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return PropertyMetadata[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getProperty(string $name): ?PropertyMetadata
    {
        return $this->properties[$name] ?? null;
    }

    /**
     * @param string $name
     * @param bool   $single
     *
     * @return \Generator|MappingInterface[]
     */
    public function getPropertyMappingsByName(string $name, bool $single = false): \Generator
    {
        foreach ($this->properties as $property => $propertyMetadata) {
            foreach ($propertyMetadata->getMappingsByName($name) as $mapping) {
                yield $property => $mapping;

                if ($single) {
                    continue 2;
                }
            }
        }
    }

    /**
     * @return MethodMetadata[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getMethod(string $name): ?MethodMetadata
    {
        return $this->methods[$name] ?? null;
    }

    /**
     * @param string $name
     * @param bool   $single
     *
     * @return \Generator|MappingInterface[]
     */
    public function getMethodMappingsByName(string $name, bool $single = false): \Generator
    {
        foreach ($this->methods as $method => $methodMetadata) {
            foreach ($methodMetadata->getMappingsByName($name) as $mapping) {
                yield $method => $mapping;

                if ($single) {
                    continue 2;
                }
            }
        }
    }
}
