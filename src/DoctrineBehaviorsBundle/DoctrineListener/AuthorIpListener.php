<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ruwork\DoctrineBehaviorsBundle\AuthorIp\AuthorIpProviderInterface;
use Ruwork\DoctrineBehaviorsBundle\Exception\NotMappedException;
use Ruwork\DoctrineBehaviorsBundle\Mapping\AuthorIp;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;

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
        $metadata = $args
            ->getEntityManager()
            ->getClassMetadata($class);

        /** @var AuthorIp[] $authorIps */
        $authorIps = $this->metadataFactory
            ->getMetadata($class)
            ->getPropertiesMappings(AuthorIp::getName());

        foreach ($authorIps as $property => $authorIp) {
            if (!$metadata->hasField($property) && !$metadata->hasAssociation($property)) {
                throw new NotMappedException($class, $property);
            }

            if ($authorIp->overwrite || !$metadata->getFieldValue($entity, $property)) {
                $value = $this->provider->getAuthorIp($metadata, $property);
                $metadata->setFieldValue($entity, $property, $value);
            }
        }
    }
}
