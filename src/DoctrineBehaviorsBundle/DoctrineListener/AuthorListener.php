<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\AnnotationTools\Factory\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Author\AuthorProviderInterface;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\Author;

final class AuthorListener implements EventSubscriber
{
    private $metadataFactory;
    private $provider;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        AuthorProviderInterface $provider
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->provider = $provider;
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

        /** @var Author[] $authors */
        $authors = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappingsByName(Author::getName(), true);

        foreach ($authors as $property => $author) {
            if (!$entityMetadata->hasField($property) && !$entityMetadata->hasAssociation($property)) {
                throw new NotMappedException($class, $property);
            }

            if ($author->overwrite || !$entityMetadata->getFieldValue($entity, $property)) {
                $value = $this->provider->getAuthor($entityMetadata, $property);
                $entityMetadata->setFieldValue($entity, $property, $value);
            }
        }
    }
}
