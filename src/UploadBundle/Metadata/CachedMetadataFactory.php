<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

use Psr\Cache\CacheItemPoolInterface;
use Ruwork\UploadBundle\Exception\NotMappedException;

final class CachedMetadataFactory implements MetadataFactoryInterface
{
    private $factory;
    private $cache;
    private $debug;

    public function __construct(
        MetadataFactoryInterface $factory,
        CacheItemPoolInterface $cache,
        bool $debug = false
    ) {
        $this->factory = $factory;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(string $class): Metadata
    {
        $item = $this->cache->getItem(\str_replace('\\', '.', $class));

        if ($item->isHit()) {
            [$mTime, $metadata] = $item->get();

            if (!$this->debug || $mTime >= $classMTime = $this->getClassMTime($class)) {
                if (null === $metadata) {
                    throw new NotMappedException(\sprintf(
                        'Class "%s" is not a mapped upload.',
                        $class
                    ));
                }

                return $metadata;
            }
        }

        $metadata = null;

        try {
            return $metadata = $this->factory->getMetadata($class);
        } finally {
            $item->set([$classMTime ?? $this->getClassMTime($class), $metadata]);
            $this->cache->save($item);
        }
    }

    private function getClassMTime(string $class): int
    {
        return \filemtime((new \ReflectionClass($class))->getFileName());
    }
}
