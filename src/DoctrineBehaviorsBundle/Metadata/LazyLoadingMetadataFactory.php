<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Psr\Cache\CacheItemPoolInterface;

class LazyLoadingMetadataFactory implements MetadataFactoryInterface
{
    private $factory;
    private $cache;
    private $metadata = [];

    public function __construct(MetadataFactoryInterface $factory, CacheItemPoolInterface $cache = null)
    {
        $this->factory = $factory;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(string $class): Metadata
    {
        if (isset($this->metadata[$class])) {
            return $this->metadata[$class];
        }

        return $this->metadata[$class] = $this->loadMetadata($class);
    }

    private function loadMetadata(string $class): Metadata
    {
        if (null !== $this->cache) {
            $item = $this->cache->getItem($this->escapeClassName($class));

            if ($item->isHit()) {
                return $item->get();
            }
        }

        $metadata = $this->factory->getMetadata($class);

        if (isset($item)) {
            $item->set($metadata);
            $this->cache->save($item);
        }

        return $metadata;
    }

    private function escapeClassName(string $class): string
    {
        return str_replace('\\', '.', $class);
    }
}
