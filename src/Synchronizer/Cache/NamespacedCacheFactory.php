<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Cache;

use Psr\SimpleCache\CacheInterface;

final class NamespacedCacheFactory implements NamespacedCacheFactoryInterface
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function createCache(string $namespace): CacheInterface
    {
        return new NamespacedCache($this->cache, $namespace);
    }
}
