<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Psr\SimpleCache\CacheInterface;
use Ruwork\Synchronizer\IdExtractor\IdExtractorInterface;
use Ruwork\Synchronizer\Provider\ByIdProviderInterface;
use Ruwork\Synchronizer\Provider\ProviderInterface;

final class Container
{
    private $provider;
    private $idExtractor;
    private $byIdProvider;
    private $cache;
    private $allLoaded = false;
    private $idsMap = [];

    public function __construct(
        ?ProviderInterface $provider,
        IdExtractorInterface $idExtractor,
        ?ByIdProviderInterface $byIdProvider,
        CacheInterface $cache
    ) {
        $this->provider = $provider;
        $this->idExtractor = $idExtractor;
        $this->byIdProvider = $byIdProvider;
        $this->cache = $cache;
    }

    public function getIdsMap(): array
    {
        if (!$this->allLoaded) {
            $this->loadAll();
        }

        return $this->idsMap;
    }

    public function getOneById($id)
    {
        $item = $this->cache->get($id);

        if (null !== $item || $this->allLoaded) {
            return $item;
        }

        if (null !== $this->byIdProvider) {
            $item = $this->byIdProvider->getOneById($id);

            $this->idsMap[$id] = true;
            $this->cache->set($id, $item);

            return $item;
        }

        $this->loadAll();

        return $this->cache->get($id);
    }

    public function clear(): void
    {
        $this->cache->deleteMultiple(array_keys($this->idsMap));
        $this->idsMap = [];
        $this->allLoaded = false;
    }

    private function loadAll(): void
    {
        $this->idsMap = [];

        foreach ($this->provider->getAll() as $item) {
            $id = $this->idExtractor->extractId($item);
            $this->cache->set($id, $item);
            $this->idsMap[$id] = true;
        }

        $this->allLoaded = true;
    }
}
