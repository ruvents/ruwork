<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\DoctrineBehaviorsBundle\EventListener\MultilingualRequestListener;
use Ruwork\DoctrineBehaviorsBundle\Mapping\Multilingual;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Multilingual\CurrentLocaleAwareInterface;

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
            Events::postLoad,
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof CurrentLocaleAwareInterface) {
            $this->requestListener->register($entity);
        }

        $class = ClassUtils::getClass($entity);
        $metadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);
        $propertyMultilinguals = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappings(Multilingual::getName());

        /** @var Multilingual $multilingual */
        foreach ($propertyMultilinguals as $property => $multilingual) {
            $value = $metadata->getFieldValue($entity, $property);

            if ($value instanceof CurrentLocaleAwareInterface) {
                $this->requestListener->register($value);
            }
        }
    }
}
