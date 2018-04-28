<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Psr\Container\ContainerInterface;
use Ruwork\Synchronizer\Cache\NamespacedCacheFactoryInterface;
use Ruwork\Synchronizer\Type\Configurator;
use Ruwork\Synchronizer\Type\TypeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Context implements ContextInterface
{
    private $types;
    private $cacheFactory;
    private $eventDispatcher;
    private $synchronizers = [];
    private $attributes;

    public function __construct(
        ContainerInterface $types,
        NamespacedCacheFactoryInterface $cacheFactory,
        EventDispatcherInterface $eventDispatcher,
        array $attributes = []
    ) {
        $this->types = $types;
        $this->cacheFactory = $cacheFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->attributes = $attributes;
    }

    public function __destruct()
    {
        /** @var SynchronizerInterface $synchronizer */
        foreach ($this->synchronizers as $synchronizer) {
            $synchronizer->clearCache();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSynchronizer(string $type): SynchronizerInterface
    {
        if (isset($this->synchronizers[$type])) {
            return $this->synchronizers[$type];
        }

        /** @var TypeInterface $typeObject */
        $typeObject = $this->types->get($type);
        $configurator = new Configurator();
        $typeObject->configure($configurator);

        return $this->synchronizers[$type] = new Synchronizer(
            $type,
            $this->eventDispatcher,
            $this,
            $configurator,
            $this->cacheFactory->createCache(self::class.$type)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }
}
