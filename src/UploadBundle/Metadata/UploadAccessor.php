<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

final class UploadAccessor
{
    private $metadataFactory;
    private $reflectionProperties = [];

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param object $object
     */
    public function getPath($object): ?string
    {
        $metadata = $this->metadataFactory->getMetadata(get_class($object));

        return $this->getReflectionProperty($metadata->getClass(), $metadata->getPathProperty())->getValue($object);
    }

    /**
     * @param object $object
     */
    public function setPath($object, string $path): void
    {
        $metadata = $this->metadataFactory->getMetadata(get_class($object));

        $this->getReflectionProperty($metadata->getClass(), $metadata->getPathProperty())->setValue($object, $path);
    }

    /**
     * @param object $object
     */
    public function getAttributes($object): array
    {
        $attributes = [];
        $metadata = $this->metadataFactory->getMetadata(get_class($object));
        $class = $metadata->getClass();

        foreach ($metadata->getAttributes() as $property => $attributeMapping) {
            $attributes[$attributeMapping->name] = $this->getReflectionProperty($class, $property)->getValue($object);
        }

        return $attributes;
    }

    /**
     * @param object $object
     */
    public function setAttributes($object, array $attributes): void
    {
        $metadata = $this->metadataFactory->getMetadata(get_class($object));
        $class = $metadata->getClass();

        foreach ($metadata->getAttributes() as $property => $attributeMapping) {
            if (array_key_exists($attributeMapping->name, $attributes)) {
                $this->getReflectionProperty($class, $property)->setValue($object, $attributes[$attributeMapping->name]);
            }
        }
    }

    private function getReflectionProperty(string $class, string $property): \ReflectionProperty
    {
        if (isset($this->reflectionProperties[$class][$property])) {
            return $this->reflectionProperties[$class][$property];
        }

        $reflectionProperty = new \ReflectionProperty($class, $property);
        $reflectionProperty->setAccessible(true);

        return $this->reflectionProperties[$class][$property] = $reflectionProperty;
    }
}
