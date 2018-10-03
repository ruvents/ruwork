<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Event;

use Ruwork\Reminder\Item\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

final class ReminderEvent extends Event
{
    private $providerName;
    private $time;
    private $item;

    public function __construct(string $providerName, \DateTimeImmutable $time, ItemInterface $item)
    {
        $this->providerName = $providerName;
        $this->time = $time;
        $this->item = $item;
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    public function getItem(): ItemInterface
    {
        return $this->item;
    }

    public function getId(): string
    {
        return $this->item->getId();
    }

    public function getValue()
    {
        return $this->item->getData();
    }
}
