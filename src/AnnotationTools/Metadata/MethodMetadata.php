<?php

declare(strict_types=1);

namespace Ruwork\AnnotationTools\Metadata;

final class MethodMetadata
{
    use MetadataTrait;

    private $name;

    public function __construct(string $name, array $mappings)
    {
        $this->name = $name;
        $this->mappings = $mappings;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
