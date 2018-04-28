<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Psr\Container\ContainerInterface;
use Ruwork\Synchronizer\Cache\NamespacedCacheFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\ImmutableEventDispatcher;

final class ContextBuilder implements ContextBuilderInterface
{
    private $types;
    private $cacheFactory;
    private $eventDispatcher;
    private $attributes = [];

    public function __construct(
        ContainerInterface $types,
        NamespacedCacheFactoryInterface $cacheFactory
    ) {
        $this->types = $types;
        $this->cacheFactory = $cacheFactory;
        $this->eventDispatcher = new EventDispatcher();
    }

    /**
     * {@inheritdoc}
     */
    public function addEventListener(string $eventName, callable $listener, int $priority = 0)
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createContext(): ContextInterface
    {
        return new Context(
            $this->types,
            $this->cacheFactory,
            new ImmutableEventDispatcher($this->eventDispatcher),
            $this->attributes
        );
    }
}
