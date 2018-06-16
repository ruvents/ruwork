<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

use Doctrine\Common\Annotations\Reader;
use Ruwork\UploadBundle\Exception\NotMappedException;
use Ruwork\UploadBundle\Metadata\Annotations\Attribute;
use Ruwork\UploadBundle\Metadata\Annotations\Path;

final class MetadataFactory implements MetadataFactoryInterface
{
    private $annotationsReader;

    public function __construct(Reader $annotationsReader)
    {
        $this->annotationsReader = $annotationsReader;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(string $class): Metadata
    {
        $reflection = new \ReflectionClass($class);
        $pathProperty = null;
        $attributes = [];

        foreach ($reflection->getProperties() as $reflectionProperty) {
            $property = $reflectionProperty->getName();

            foreach ($this->annotationsReader->getPropertyAnnotations($reflectionProperty) as $annotation) {
                if ($annotation instanceof Path) {
                    $pathProperty = $property;
                } elseif ($annotation instanceof Attribute) {
                    $attributes[$property] = $annotation;
                }
            }
        }

        if (null === $pathProperty) {
            throw new NotMappedException(sprintf('Upload class "%s" must have a @Path property.', $class));
        }

        return new Metadata($class, $pathProperty, $attributes);
    }
}
