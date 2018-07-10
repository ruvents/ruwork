<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Ruwork\UploadBundle\Exception\NotMappedException;
use Ruwork\UploadBundle\Exception\NotRegisteredException;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;

final class UploadListener implements EventSubscriber
{
    private $manager;

    public function __construct(UploadManagerInterface $manager)
    {
        $this->manager = $manager;
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
            try {
                $this->manager->save($entity);
            } catch (NotRegisteredException $exception) {
            }
        }

        foreach ($unitOfWork->getScheduledEntityDeletions() as $entity) {
            try {
                $this->manager->delete($entity);
            } catch (NotMappedException $exception) {
            }
        }
    }
}
