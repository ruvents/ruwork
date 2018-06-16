<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;

final class UploadListener implements EventSubscriber
{
    private $uploadManager;

    public function __construct(UploadManagerInterface $uploadManager)
    {
        $this->uploadManager = $uploadManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if ($this->uploadManager->isRegistered($entity)) {
                $this->uploadManager->save($entity);
            }
        }

        foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
            if ($this->uploadManager->isUpload($entity)) {
                $this->uploadManager->delete($entity);
            }
        }
    }
}
