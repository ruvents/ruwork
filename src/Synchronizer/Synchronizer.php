<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

use Psr\SimpleCache\CacheInterface;
use Ruwork\Synchronizer\Event\CompleteEvent;
use Ruwork\Synchronizer\Event\PreSyncEvent;
use Ruwork\Synchronizer\Event\SyncEvent;
use Ruwork\Synchronizer\Event\SyncEvents;
use Ruwork\Synchronizer\Type\ConfigurationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Synchronizer implements SynchronizerInterface
{
    private const ROOT = 'root';

    private $type;
    private $eventDispatcher;
    private $context;
    private $configuration;
    private $container;

    public function __construct(
        string $type,
        EventDispatcherInterface $eventDispatcher,
        ContextInterface $context,
        ConfigurationInterface $configuration,
        CacheInterface $cache
    ) {
        $this->type = $type;
        $this->eventDispatcher = $eventDispatcher;
        $this->context = $context;
        $this->configuration = $configuration;
        $this->container = new Container(
            $configuration->getSourceProvider(),
            $configuration->getSourceIdExtractor(),
            $configuration->getSourceByIdProvider(),
            $cache
        );
    }

    /**
     * {@inheritdoc}
     */
    public function syncAll(): void
    {
        \iterator_count($this->syncAndYieldAll());
    }

    /**
     * {@inheritdoc}
     */
    public function syncAndYieldAll(): \Generator
    {
        $root = false;

        if (!$this->context->getAttribute(self::ROOT, false)) {
            $root = true;
            $this->context->setAttribute(self::ROOT, true);
        }

        $sourceIdsMap = $this->container->getIdsMap();
        $targets = $this->configuration->getTargetProvider()->getAll();

        foreach ($targets as $target) {
            $id = $this->configuration
                ->getTargetIdExtractor()
                ->extractId($target);

            if (isset($sourceIdsMap[$id])) {
                yield $this->update($root, $id, $this->container->getOneById($id), $target);
                unset($sourceIdsMap[$id]);
            } else {
                $this->delete($root, $id, $target);
            }
        }

        foreach ($sourceIdsMap as $id => $nb) {
            yield $this->create($root, $id, $this->container->getOneById($id));
        }

        if ($root) {
            $this->complete();
            $this->context->setAttribute(self::ROOT, false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function syncTarget($target)
    {
        return $this->syncOne(null, null, $target);
    }

    /**
     * {@inheritdoc}
     */
    public function syncSource($source, bool $lazy = false)
    {
        return $this->syncOne(null, $source, null, $lazy);
    }

    /**
     * {@inheritdoc}
     */
    public function syncById($id, bool $lazy = false)
    {
        return $this->syncOne($id, null, null, $lazy);
    }

    /**
     * {@inheritdoc}
     */
    public function clearCache(): void
    {
        $this->container->clear();
    }

    private function syncOne($id, $source, $target, bool $lazy = false)
    {
        $root = false;

        if (!$this->context->getAttribute(self::ROOT, false)) {
            $root = true;
            $this->context->setAttribute(self::ROOT, true);
        }

        if (null === $id) {
            if (null !== $source) {
                $id = $this->configuration
                    ->getSourceIdExtractor()
                    ->extractId($source);
            } else {
                $id = $this->configuration
                    ->getTargetIdExtractor()
                    ->extractId($target);
            }
        }

        if (null === $target) {
            $target = $this->configuration
                ->getTargetByIdProvider()
                ->getOneById($id);
        }

        if ($lazy && null !== $target) {
            return $target;
        }

        if (null === $source) {
            $source = $this->container->getOneById($id);
        }

        if (null !== $target) {
            if (null !== $source) {
                $target = $this->update($root, $id, $source, $target);
            } else {
                $this->delete($root, $id, $target);
                $target = null;
            }
        } elseif (null !== $source) {
            $target = $this->create($root, $id, $source);
        }

        if ($root) {
            $this->complete();
            $this->context->setAttribute(self::ROOT, false);
        }

        return $target;
    }

    private function create(bool $root, $id, $source)
    {
        $event = new PreSyncEvent($this->context, $this->type, $root, $id, $source, null);
        $this->eventDispatcher->dispatch(SyncEvents::PRE_CREATE, $event);

        if ($event->isSkipped()) {
            return null;
        }

        $target = $this->configuration
            ->getTargetCreator()
            ->create($source, $this->context);

        $event = new SyncEvent($this->context, $this->type, $root, $id, $source, $target);
        $this->eventDispatcher->dispatch(SyncEvents::POST_CREATE, $event);

        return $target;
    }

    private function update(bool $root, $id, $source, $target)
    {
        $event = new PreSyncEvent($this->context, $this->type, $root, $id, $source, $target);
        $this->eventDispatcher->dispatch(SyncEvents::PRE_UPDATE, $event);

        if ($event->isSkipped()) {
            return null;
        }

        $target = $this->configuration
                ->getTargetUpdater()
                ->update($source, $target, $this->context) ?? $target;

        $event = new SyncEvent($this->context, $this->type, $root, $id, $source, $target);
        $this->eventDispatcher->dispatch(SyncEvents::POST_UPDATE, $event);

        return $target;
    }

    private function delete(bool $root, $id, $target): void
    {
        $event = new PreSyncEvent($this->context, $this->type, $root, $id, null, $target);
        $this->eventDispatcher->dispatch(SyncEvents::PRE_DELETE, $event);

        if ($event->isSkipped()) {
            return;
        }

        $this->configuration
            ->getTargetDeleter()
            ->delete($target, $this->context);

        $event = new SyncEvent($this->context, $this->type, $root, $id, null, $target);
        $this->eventDispatcher->dispatch(SyncEvents::POST_DELETE, $event);
    }

    private function complete(): void
    {
        $event = new CompleteEvent($this->context, $this->type);
        $this->eventDispatcher->dispatch(SyncEvents::ON_COMPLETE, $event);
    }
}
