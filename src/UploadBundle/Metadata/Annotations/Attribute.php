<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Attribute
{
    /**
     * @Required
     *
     * @var string
     */
    public $name;
}
