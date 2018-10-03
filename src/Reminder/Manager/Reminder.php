<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Manager;

use Psr\Container\ContainerInterface;
use Ruwork\Reminder\Event\ReminderEvent;
use Ruwork\Reminder\Event\ReminderEvents;
use Ruwork\Reminder\Item\ClearableItemInterface;
use Ruwork\Reminder\Item\ItemInterface;
use Ruwork\Reminder\Marker\MarkerInterface;
use Ruwork\Reminder\Provider\ProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Reminder implements ReminderInterface
{
    private $dispatcher;
    private $marker;
    private $providers;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        MarkerInterface $marker,
        ContainerInterface $providers
    ) {
        $this->dispatcher = $dispatcher;
        $this->marker = $marker;
        $this->providers = $providers;
    }

    /**
     * {@inheritdoc}
     */
    public function remind(string $providerName, ?\DateTimeImmutable $now = null): void
    {
        $now = $now ?? new \DateTimeImmutable();

        /** @var ProviderInterface $provider */
        $provider = $this->providers->get($providerName);

        /** @var ItemInterface $item */
        foreach ($provider->getItems($now) as $item) {
            $markerId = $providerName.':'.$item->getId();

            if ($this->marker->isMarked($markerId)) {
                continue;
            }

            $event = new ReminderEvent($providerName, $now, $item);
            $this->dispatcher->dispatch(ReminderEvents::remind($providerName), $event);

            $this->marker->mark($markerId);

            if ($item instanceof ClearableItemInterface) {
                $item->clear();
            }
        }
    }
}
