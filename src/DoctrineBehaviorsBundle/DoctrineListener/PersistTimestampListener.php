<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\DoctrineBehaviorsBundle\Mapping\PersistTimestamp;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;

final class PersistTimestampListener implements EventSubscriber
{
    private $metadataFactory;

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $class = ClassUtils::getClass($entity);
        $metadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);

        /** @var PersistTimestamp[] $timestamps */
        $timestamps = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappings(PersistTimestamp::getName());

        foreach ($timestamps as $property => $timestamp) {
            if ($timestamp->overwrite || !$metadata->getFieldValue($entity, $property)) {
                $type = (string) $metadata->getTypeOfField($property);
                $value = false !== strpos($type, 'immutable')
                    ? new \DateTimeImmutable()
                    : new \DateTime();
                $metadata->setFieldValue($entity, $property, $value);
            }
        }
    }
}
