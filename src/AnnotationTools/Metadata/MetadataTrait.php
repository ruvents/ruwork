<?php

declare(strict_types=1);

namespace Ruwork\AnnotationTools\Metadata;

use Ruwork\AnnotationTools\Mapping\MappingInterface;

trait MetadataTrait
{
    protected $mappings = [];

    /**
     * @return MappingInterface[]
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    public function hasMapping(string $name): bool
    {
        return isset($this->mappings[$name]);
    }

    /**
     * @return MappingInterface[]
     */
    public function getMappingsByName(string $name): array
    {
        return $this->mappings[$name] ?? [];
    }

    public function getMappingByName(string $name): ?MappingInterface
    {
        return $this->mappings[$name] ?? null;
    }
}
