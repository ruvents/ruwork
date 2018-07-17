<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Mapping;

/**
 * @Annotation()
 * @Target("PROPERTY")
 */
final class Multilingual implements MappingInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getName(): string
    {
        return 'multilingual';
    }
}
