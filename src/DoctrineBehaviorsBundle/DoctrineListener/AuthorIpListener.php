<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\AnnotationTools\Factory\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\AuthorIp\AuthorIpProviderInterface;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\AuthorIp;

final class AuthorIpListener implements EventSubscriber
{
    private $metadataFactory;
    private $provider;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        AuthorIpProviderInterface $provider
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

        /** @var AuthorIp[] $authorIps */
        $authorIps = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertyMappingsByName(AuthorIp::getName(), true);

        foreach ($authorIps as $property => $authorIp) {
            if (!$entityMetadata->hasField($property) && !$entityMetadata->hasAssociation($property)) {
                throw new NotMappedException($class, $property);
            }

            if ($authorIp->overwrite || !$entityMetadata->getFieldValue($entity, $property)) {
                $value = $this->provider->getAuthorIp($entityMetadata, $property);
                $entityMetadata->setFieldValue($entity, $property, $value);
            }
        }
    }
}
