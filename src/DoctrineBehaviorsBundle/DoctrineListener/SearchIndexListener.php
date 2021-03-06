<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\AnnotationTools\Factory\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\SearchIndex;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class SearchIndexListener implements EventSubscriber
{
    private $metadataFactory;
    private $accessor;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        PropertyAccessorInterface $accessor
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $class = ClassUtils::getClass($entity);
        $entityMetadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);

        /** @var SearchIndex[] $searchIndices */
        $searchIndices = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappingsByName(SearchIndex::getName(), true);

        foreach ($searchIndices as $property => $searchIndex) {
            if (!$entityMetadata->hasField($property)) {
                throw new NotMappedException($class, $property);
            }

            $values = [];

            foreach ($searchIndex->paths as $path) {
                if ($this->accessor->isReadable($entity, $path)) {
                    $value = $this->accessor->getValue($entity, $path);
                    $this->processValue($value, $values);
                }
            }

            $entityMetadata->setFieldValue($entity, $property, $values);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->prePersist($args);
    }

    private function processValue($value, array &$values): void
    {
        if (is_iterable($value)) {
            foreach ($value as $item) {
                $this->processValue($item, $values);
            }

            return;
        }

        if (\is_string($value) || \is_int($value) || \is_float($value)) {
            $values[] = $value;

            return;
        }

        if (\is_object($value) && method_exists($value, '__toString')) {
            $values[] = $value;
        }
    }
}
