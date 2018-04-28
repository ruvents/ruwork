<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Type;

use Ruwork\Synchronizer\Handler\CreatorInterface;
use Ruwork\Synchronizer\Handler\DeleterInterface;
use Ruwork\Synchronizer\Handler\DummyCreator;
use Ruwork\Synchronizer\Handler\DummyDeleter;
use Ruwork\Synchronizer\Handler\DummyUpdater;
use Ruwork\Synchronizer\Handler\UpdaterInterface;
use Ruwork\Synchronizer\IdExtractor\IdExtractorInterface;
use Ruwork\Synchronizer\Provider\ByIdProviderInterface;
use Ruwork\Synchronizer\Provider\ProviderInterface;

final class Configurator implements ConfiguratorInterface
{
    private $sourceProvider;
    private $sourceIdExtractor;
    private $sourceByIdProvider;
    private $targetProvider;
    private $targetIdExtractor;
    private $targetByIdProvider;
    private $targetCreator;
    private $targetUpdater;
    private $targetDeleter;

    public function __construct()
    {
        $this->targetCreator = new DummyCreator();
        $this->targetUpdater = new DummyUpdater();
        $this->targetDeleter = new DummyDeleter();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceProvider(): ?ProviderInterface
    {
        return $this->sourceProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceProvider(?ProviderInterface $provider)
    {
        $this->sourceProvider = $provider;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceIdExtractor(): IdExtractorInterface
    {
        return $this->sourceIdExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceIdExtractor(IdExtractorInterface $extractor)
    {
        $this->sourceIdExtractor = $extractor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceByIdProvider(): ?ByIdProviderInterface
    {
        return $this->sourceByIdProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceByIdProvider(?ByIdProviderInterface $provider)
    {
        $this->sourceByIdProvider = $provider;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetProvider(): ?ProviderInterface
    {
        return $this->targetProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetProvider(?ProviderInterface $provider)
    {
        $this->targetProvider = $provider;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetIdExtractor(): IdExtractorInterface
    {
        return $this->targetIdExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetIdExtractor(IdExtractorInterface $extractor)
    {
        $this->targetIdExtractor = $extractor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetByIdProvider(): ?ByIdProviderInterface
    {
        return $this->targetByIdProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetByIdProvider(?ByIdProviderInterface $provider)
    {
        $this->targetByIdProvider = $provider;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetCreator(): CreatorInterface
    {
        return $this->targetCreator;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetCreator(CreatorInterface $creator)
    {
        $this->targetCreator = $creator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetUpdater(): UpdaterInterface
    {
        return $this->targetUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetUpdater(UpdaterInterface $updater)
    {
        $this->targetUpdater = $updater;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetDeleter(): DeleterInterface
    {
        return $this->targetDeleter;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetDeleter(DeleterInterface $deleter)
    {
        $this->targetDeleter = $deleter;

        return $this;
    }
}
