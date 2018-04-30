<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Ruwork\DoctrineBehaviorsBundle\Mapping\MappingInterface;

final class Metadata
{
    private $classMappings;
    private $propertyMappings;

    /**
     * @param MappingInterface[]   $classMappings
     * @param MappingInterface[][] $propertyMappings
     */
    public function __construct(array $classMappings, array $propertyMappings)
    {
        $this->classMappings = $classMappings;
        $this->propertyMappings = $propertyMappings;
    }

    public function getClassMapping(string $mappingName): ?MappingInterface
    {
        return $this->classMappings[$mappingName] ?? null;
    }

    /**
     * @param string $mappingName
     *
     * @return MappingInterface[]
     */
    public function getPropertyMappings(string $mappingName): array
    {
        return $this->propertyMappings[$mappingName] ?? [];
    }
}
