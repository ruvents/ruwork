<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Cache;

use Psr\SimpleCache\CacheInterface;

final class NamespacedCache implements CacheInterface
{
    private $cache;
    private $namespace;

    public function __construct(CacheInterface $cache, string $namespace)
    {
        $this->cache = $cache;
        $this->namespace = \dechex(\crc32($namespace));
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $key = $this->getNamespacedKey($key);

        return $this->cache->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        $key = $this->getNamespacedKey($key);

        return $this->cache->set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        $key = $this->getNamespacedKey($key);

        return $this->cache->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        throw new \LogicException('Clear is not implemented for namespaced cache.');
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple($keys, $default = null)
    {
        $keys = $this->getNamespacedKeys($keys);

        return $this->cache->getMultiple($keys, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        $values = $this->getNamespacedValues($values);

        return $this->cache->setMultiple($values, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple($keys)
    {
        $keys = $this->getNamespacedKeys($keys);

        return $this->cache->deleteMultiple($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $key = $this->getNamespacedKey($key);

        return $this->cache->has($key);
    }

    private function getNamespacedKey($key): string
    {
        return $this->namespace.$key;
    }

    private function getNamespacedKeys(iterable $keys): \Generator
    {
        foreach ($keys as $key) {
            yield $this->getNamespacedKey($key);
        }
    }

    private function getNamespacedValues(iterable $values): \Generator
    {
        foreach ($values as $key => $value) {
            yield $this->getNamespacedKey($key) => $value;
        }
    }
}
