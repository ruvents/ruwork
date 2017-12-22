<?php
declare(strict_types=1);

namespace Ruwork\UploadBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Ruwork\UploadBundle\Entity\AbstractUpload;
use Ruwork\UploadBundle\UploadManager;

class UploadDoctrineListener implements EventSubscriber
{
    private $manager;

    public function __construct(UploadManager $manager)
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

    public function onFlush(OnFlushEventArgs $args)
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof AbstractUpload) {
                $this->manager->saveUpload($entity);
            }
        }
    }
}
