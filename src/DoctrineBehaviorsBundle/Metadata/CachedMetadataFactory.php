<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Metadata;

use Psr\Cache\CacheItemPoolInterface;

final class CachedMetadataFactory implements MetadataFactoryInterface
{
    private $factory;
    private $cache;
    private $metadatas = [];

    public function __construct(MetadataFactoryInterface $factory, CacheItemPoolInterface $cache)
    {
        $this->factory = $factory;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(string $class): Metadata
    {
        if (isset($this->metadatas[$class])) {
            return $this->metadatas[$class];
        }

        $item = $this->cache->getItem(str_replace('\\', '.', $class));

        if ($item->isHit()) {
            return $this->metadatas[$class] = $item->get();
        }

        $metadata = $this->metadatas[$class] = $this->factory->getMetadata($class);
        $item->set($metadata);

        $this->cache->save($item);

        return $metadata;
    }
}
