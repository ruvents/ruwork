<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Cache;

use Psr\SimpleCache\CacheInterface;

interface NamespacedCacheFactoryInterface
{
    public function createCache(string $namespace): CacheInterface;
}
