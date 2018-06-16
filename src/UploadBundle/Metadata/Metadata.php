<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

use Ruwork\UploadBundle\Metadata\Annotations\Attribute;

final class Metadata
{
    private $class;
    private $pathProperty;
    private $attributes;

    public function __construct(string $class, string $pathProperty, array $attributes)
    {
        $this->class = $class;
        $this->pathProperty = $pathProperty;
        $this->attributes = $attributes;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPathProperty(): string
    {
        return $this->pathProperty;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
