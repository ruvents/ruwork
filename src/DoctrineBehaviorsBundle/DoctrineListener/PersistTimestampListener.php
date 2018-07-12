<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\AnnotationTools\Factory\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\PersistTimestamp;

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
        $entityMetadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);

        /** @var PersistTimestamp[] $timestamps */
        $timestamps = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappingsByName(PersistTimestamp::getName(), true);

        foreach ($timestamps as $property => $timestamp) {
            if (!$entityMetadata->hasField($property)) {
                throw new NotMappedException($class, $property);
            }

            if ($timestamp->overwrite || !$entityMetadata->getFieldValue($entity, $property)) {
                $type = (string) $entityMetadata->getTypeOfField($property);
                $value = false !== \strpos($type, 'immutable')
                    ? new \DateTimeImmutable()
                    : new \DateTime();
                $entityMetadata->setFieldValue($entity, $property, $value);
            }
        }
    }
}
