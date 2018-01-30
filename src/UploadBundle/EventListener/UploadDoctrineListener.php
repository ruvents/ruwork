<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Ruwork\UploadBundle\Entity\AbstractUpload;
use Ruwork\UploadBundle\PathGenerator\PathGeneratorInterface;
use Ruwork\UploadBundle\UploadManager;

class UploadDoctrineListener implements EventSubscriber
{
    private $pathGenerator;
    private $manager;
    private $setter;

    public function __construct(PathGeneratorInterface $pathGenerator, UploadManager $manager)
    {
        $this->pathGenerator = $pathGenerator;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onPrePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof AbstractUpload) {
            $path = $this->pathGenerator->generatePath($entity->getUploadedFile());
            $this->setValue($entity, 'path', $path);
        }
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof AbstractUpload) {
                $this->saveUpload($entity);
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof AbstractUpload) {
                $this->setValue($entity, 'uploadedFile', null);
            }
        }
    }

    private function saveUpload(AbstractUpload $upload): void
    {
        $pathname = $this->manager->getPathname($upload);
        $upload
            ->getUploadedFile()
            ->move(dirname($pathname), basename($pathname));
    }

    private function setValue($upload, $property, $value): void
    {
        if (null === $this->setter) {
            $this->setter = \Closure::bind(function ($upload, $property, $value): void {
                $upload->$property = $value;
            }, null, AbstractUpload::class);
        }

        ($this->setter)($upload, $property, $value);
    }
}
