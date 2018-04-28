<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface ContextBuilderInterface
{
    /**
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     *
     * @return static
     */
    public function addEventListener(string $eventName, callable $listener, int $priority = 0);

    /**
     * @param EventSubscriberInterface $subscriber
     *
     * @return static
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber);

    /**
     * @param array $attributes
     *
     * @return static
     */
    public function setAttributes(array $attributes);

    public function createContext(): ContextInterface;
}
