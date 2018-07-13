<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface ContextBuilderInterface
{
    /**
     * @return static
     */
    public function addEventListener(string $eventName, callable $listener, int $priority = 0);

    /**
     * @return static
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber);

    /**
     * @return static
     */
    public function setAttributes(array $attributes);

    public function createContext(): ContextInterface;
}
