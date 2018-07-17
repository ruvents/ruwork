<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Mapping;

/**
 * @Annotation()
 * @Target("PROPERTY")
 */
final class PersistTimestamp implements MappingInterface
{
    /**
     * @var bool
     */
    public $overwrite = true;

    /**
     * {@inheritdoc}
     */
    public static function getName(): string
    {
        return 'persist';
    }
}
