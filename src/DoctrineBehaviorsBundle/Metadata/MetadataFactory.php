<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Ruwork\AnnotationTools\Factory\AbstractMetadataFactory;
use Ruwork\DoctrineBehaviorsBundle\Mapping\MappingInterface;

final class MetadataFactory extends AbstractMetadataFactory
{
    /**
     * {@inheritdoc}
     */
    protected function getTargets(): array
    {
        return [
            self::TARGET_PROPERTY,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($mapping, int $target): bool
    {
        return $mapping instanceof MappingInterface;
    }
}
