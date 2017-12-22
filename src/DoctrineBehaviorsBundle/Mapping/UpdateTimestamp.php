<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Mapping;

/**
 * @Annotation()
 * @Target("PROPERTY")
 */
final class UpdateTimestamp
{
    /**
     * @var bool
     */
    public $overwrite = true;
}
