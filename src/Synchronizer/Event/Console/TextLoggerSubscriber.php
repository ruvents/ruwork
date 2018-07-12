<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event\Console;

use Ruwork\Synchronizer\Event\CompleteEvent;
use Ruwork\Synchronizer\Event\SyncEvent;
use Ruwork\Synchronizer\Event\SyncEvents;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TextLoggerSubscriber implements EventSubscriberInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SyncEvents::POST_CREATE => 'postCreate',
            SyncEvents::POST_UPDATE => 'postUpdate',
            SyncEvents::POST_DELETE => 'postDelete',
            SyncEvents::ON_COMPLETE => 'onComplete',
        ];
    }

    public function postCreate(SyncEvent $event)
    {
        $this->output->writeln(\sprintf('<fg=green>%s: created #%s.</>', $event->getType(), $event->getId()));
    }

    public function postUpdate(SyncEvent $event)
    {
        $this->output->writeln(\sprintf('<fg=yellow>%s: updated #%s.</>', $event->getType(), $event->getId()));
    }

    public function postDelete(SyncEvent $event)
    {
        $this->output->writeln(\sprintf('<fg=red>%s: deleted #%s.</>', $event->getType(), $event->getId()));
    }

    public function onComplete(CompleteEvent $event)
    {
        $this->output->writeln(\sprintf('<fg=green>%s: complete.</>', $event->getType()));
    }
}
