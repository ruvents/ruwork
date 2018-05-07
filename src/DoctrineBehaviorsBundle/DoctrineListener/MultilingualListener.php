<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\AnnotationTools\Factory\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\EventListener\MultilingualRequestListener;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\Multilingual;
use Ruwork\DoctrineBehaviorsBundle\Multilingual\MultilingualInterface;

final class MultilingualListener implements EventSubscriber
{
    private $metadataFactory;
    private $requestListener;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        MultilingualRequestListener $requestListener
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->requestListener = $requestListener;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::postLoad,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $class = ClassUtils::getClass($entity);
        $entityMetadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);

        if ($entity instanceof MultilingualInterface) {
            $this->requestListener->register($entity);
        }

        $multilinguals = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappingsByName(Multilingual::getName(), true);

        foreach ($multilinguals as $property => $multilingual) {
            if (!$entityMetadata->hasField($property) && !$entityMetadata->hasAssociation($property)) {
                throw new NotMappedException($class, $property);
            }

            $value = $entityMetadata->getFieldValue($entity, $property);

            if ($value instanceof MultilingualInterface) {
                $this->requestListener->register($value);
            }
        }
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->prePersist($args);
    }
}
