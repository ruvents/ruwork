<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Ruwork\DoctrineBehaviorsBundle\Mapping\MappingInterface;

final class Metadata
{
    private $classMappings;
    private $propertiesMappings;

    public function __construct(array $classMappings, array $propertiesMappings)
    {
        $this->classMappings = $classMappings;
        $this->propertiesMappings = $propertiesMappings;
    }

    public function getClassMapping(string $mapping): ?MappingInterface
    {
        return $this->classMappings[$mapping] ?? null;
    }

    /**
     * @param string $mapping
     *
     * @return MappingInterface[]
     */
    public function getPropertiesMappings(string $mapping): array
    {
        return $this->propertiesMappings[$mapping] ?? [];
    }

    public function getPropertyMapping(string $property, string $mapping): ?MappingInterface
    {
        return $this->propertiesMappings[$mapping][$property] ?? null;
    }
}
