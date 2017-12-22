<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Strategy\TimestampStrategy\TimestampStrategyInterface;

class UpdateTimestampListener
{
    use DefaultMappingTrait;

    private $factory;
    private $strategy;

    public function __construct(MetadataFactoryInterface $factory, TimestampStrategyInterface $strategy)
    {
        $this->factory = $factory;
        $this->strategy = $strategy;
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $class = get_class($entity);
        $metadata = $args->getEntityManager()->getClassMetadata($class);
        $timestamps = $this->factory->getMetadata($class)->getUpdateTimestamps();

        foreach ($timestamps as $property => $timestamp) {
            if ($timestamp->overwrite || !$metadata->getFieldValue($entity, $property)) {
                $value = $this->strategy->getTimestamp($metadata, $property);
                $metadata->setFieldValue($entity, $property, $value);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getMappedProperties(string $class): iterable
    {
        return array_keys($this->factory->getMetadata($class)->getUpdateTimestamps());
    }
}
