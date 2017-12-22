<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

trait DefaultMappingTrait
{
    private $defaultMappingVariant;
    private $defaultMapping;

    public function setDefaultMapping(string $variant, array $mapping): void
    {
        $this->defaultMappingVariant = $variant;
        $this->defaultMapping = $mapping;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args): void
    {
        if (null === $this->defaultMappingVariant) {
            return;
        }

        $metadata = $args->getClassMetadata();

        foreach ($this->getMappedProperties($metadata->name) as $property) {
            if (isset($metadata->fieldMappings[$property])
                || isset($metadata->associationMappings[$property])
                || isset($metadata->embeddedClasses[$property])
            ) {
                continue;
            }

            if ('column' === $this->defaultMappingVariant) {
                $metadata->mapField([
                    'fieldName' => $property,
                    'type' => $this->defaultMapping['type'],
                    'length' => $this->defaultMapping['length'],
                    'unique' => $this->defaultMapping['unique'],
                    'nullable' => $this->defaultMapping['nullable'],
                ]);
            } elseif ('many_to_one' === $this->defaultMappingVariant) {
                $metadata->mapManyToOne([
                    'fieldName' => $property,
                    'targetEntity' => $this->defaultMapping['target_entity'],
                    'fetch' => constant(ClassMetadata::class.'::FETCH_'.$this->defaultMapping['fetch']),
                    'joinColumns' => [['nullable' => $this->defaultMapping['nullable']]],
                ]);
            } elseif ('one_to_one' === $this->defaultMappingVariant) {
                $metadata->mapOneToOne([
                    'fieldName' => $property,
                    'targetEntity' => $this->defaultMapping['target_entity'],
                    'fetch' => constant(ClassMetadata::class.'::FETCH_'.$this->defaultMapping['fetch']),
                    'orphanRemoval' => $this->defaultMapping['orphan_removal'],
                    'joinColumns' => [['nullable' => $this->defaultMapping['nullable']]],
                ]);
            } elseif ('embedded' === $this->defaultMappingVariant) {
                $metadata->mapEmbedded([
                    'fieldName' => $property,
                    'class' => $this->defaultMapping['class'],
                    'columnPrefix' => $this->defaultMapping['column_prefix'],
                ]);
                $embeddableMetadata = $args->getEntityManager()->getClassMetadata($this->defaultMapping['class']);
                $metadata->inlineEmbeddable($property, $embeddableMetadata);
            }
        }
    }

    abstract protected function getMappedProperties(string $class): iterable;
}
