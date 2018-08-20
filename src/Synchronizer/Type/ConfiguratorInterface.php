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
     * @return static
     */
    public function setSourceProvider(?ProviderInterface $provider);

    /**
     * @return static
     */
    public function setSourceIdExtractor(IdExtractorInterface $extractor);

    /**
     * @return static
     */
    public function setSourceByIdProvider(?ByIdProviderInterface $provider);

    /**
     * @return static
     */
    public function setTargetProvider(?ProviderInterface $provider);

    /**
     * @return static
     */
    public function setTargetIdExtractor(IdExtractorInterface $extractor);

    /**
     * @return static
     */
    public function setTargetByIdProvider(?ByIdProviderInterface $provider);

    /**
     * @return static
     */
    public function setTargetCreator(CreatorInterface $creator);

    /**
     * @return static
     */
    public function setTargetUpdater(UpdaterInterface $updater);

    /**
     * @return static
     */
    public function setTargetDeleter(DeleterInterface $deleter);
}
