<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\DoctrineBehaviorsBundle\Author\AuthorProviderInterface;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\Author;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;

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
        $metadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);

        /** @var Author[] $authors */
        $authors = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertiesMappings(Author::getName());

        foreach ($authors as $property => $author) {
            if (!$metadata->hasField($property) && !$metadata->hasAssociation($property)) {
                throw new NotMappedException($class, $property);
            }

            if ($author->overwrite || !$metadata->getFieldValue($entity, $property)) {
                $value = $this->provider->getAuthor($metadata, $property);
                $metadata->setFieldValue($entity, $property, $value);
            }
        }
    }
}