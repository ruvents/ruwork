<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Type;

use Ruwork\Synchronizer\Handler\CreatorInterface;
use Ruwork\Synchronizer\Handler\DeleterInterface;
use Ruwork\Synchronizer\Handler\UpdaterInterface;
use Ruwork\Synchronizer\IdExtractor\IdExtractorInterface;
use Ruwork\Synchronizer\Provider\ByIdProviderInterface;
use Ruwork\Synchronizer\Provider\ProviderInterface;

interface ConfiguratorInterface extends ConfigurationInterface
{
    /**
     * @param null|ProviderInterface $provider
     *
     * @return static
     */
    public function setSourceProvider(?ProviderInterface $provider);

    /**
     * @param IdExtractorInterface $extractor
     *
     * @return static
     */
    public function setSourceIdExtractor(IdExtractorInterface $extractor);

    /**
     * @param null|ByIdProviderInterface $provider
     *
     * @return static
     */
    public function setSourceByIdProvider(?ByIdProviderInterface $provider);

    /**
     * @param null|ProviderInterface $provider
     *
     * @return static
     */
    public function setTargetProvider(?ProviderInterface $provider);

    /**
     * @param IdExtractorInterface $extractor
     *
     * @return static
     */
    public function setTargetIdExtractor(IdExtractorInterface $extractor);

    /**
     * @param null|ByIdProviderInterface $provider
     *
     * @return static
     */
    public function setTargetByIdProvider(?ByIdProviderInterface $provider);

    /**
     * @param CreatorInterface $creator
     *
     * @return static
     */
    public function setTargetCreator(CreatorInterface $creator);

    /**
     * @param UpdaterInterface $updater
     *
     * @return static
     */
    public function setTargetUpdater(UpdaterInterface $updater);

    /**
     * @param DeleterInterface $deleter
     *
     * @return static
     */
    public function setTargetDeleter(DeleterInterface $deleter);
}
