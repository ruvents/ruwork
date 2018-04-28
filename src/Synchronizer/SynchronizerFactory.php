<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Psr\Container\ContainerInterface;
use Ruwork\Synchronizer\Cache\NamespacedCacheFactoryInterface;

final class SynchronizerFactory implements SynchronizerFactoryInterface
{
    private $types;
    private $cacheFactory;

    public function __construct(
        ContainerInterface $types,
        NamespacedCacheFactoryInterface $cacheFactory
    ) {
        $this->types = $types;
        $this->cacheFactory = $cacheFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createSynchronizer(string $type, array $attributes = []): SynchronizerInterface
    {
        return $this->createContextBuilder()
            ->setAttributes($attributes)
            ->createContext()
            ->getSynchronizer($type);
    }

    /**
     * {@inheritdoc}
     */
    public function createContextBuilder(): ContextBuilderInterface
    {
        return new ContextBuilder($this->types, $this->cacheFactory);
    }
}
