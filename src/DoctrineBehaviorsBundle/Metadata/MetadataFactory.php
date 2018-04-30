<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Doctrine\Common\Annotations\Reader;
use Ruwork\DoctrineBehaviorsBundle\Mapping\MappingInterface;

final class MetadataFactory implements MetadataFactoryInterface
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(string $class): Metadata
    {
        $reflectionClass = new \ReflectionClass($class);
        $classMappings = [];
        $propertyMappings = [];

        foreach ($this->reader->getClassAnnotations($reflectionClass) as $annotation) {
            if ($annotation instanceof MappingInterface) {
                $classMappings[$annotation::getName()] = $annotation;
            }
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();

            foreach ($this->reader->getPropertyAnnotations($reflectionProperty) as $annotation) {
                if ($annotation instanceof MappingInterface) {
                    $propertyMappings[$annotation::getName()][$name] = $annotation;
                }
            }
        }

        return new Metadata($classMappings, $propertyMappings);
    }
}
