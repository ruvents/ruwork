<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Ruwork\Synchronizer\Event\CompleteEvent;
use Ruwork\Synchronizer\Event\SyncEvent;
use Ruwork\Synchronizer\Event\SyncEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FlushSubscriber implements EventSubscriberInterface
{
    private const COUNT = 'doctrine_entity_count';

    private $entityManager;
    private $batchSize;

    public function __construct(EntityManagerInterface $entityManager, $batchSize = 50)
    {
        $this->entityManager = $entityManager;
        $this->batchSize = $batchSize;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SyncEvents::POST_CREATE => 'onEach',
            SyncEvents::POST_UPDATE => 'onEach',
            SyncEvents::POST_DELETE => 'onEach',
            SyncEvents::ON_COMPLETE => 'onComplete',
        ];
    }

    public function onEach(SyncEvent $event): void
    {
        $count = $event->getContext()->getAttribute(self::COUNT, 1);

        if ($event->isRoot() && 0 === ($count % $this->batchSize)) {
            $this->entityManager->flush();
            $this->entityManager->clear();
        }

        $event->getContext()->setAttribute(self::COUNT, $count + 1);
    }

    public function onComplete(CompleteEvent $event): void
    {
        $this->entityManager->flush();
        $this->entityManager->clear();

        $event->getContext()->setAttribute(self::COUNT, null);
    }
}
