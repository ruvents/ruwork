<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Type;

use Ruwork\Synchronizer\Handler\CreatorInterface;
use Ruwork\Synchronizer\Handler\DeleterInterface;
use Ruwork\Synchronizer\Handler\UpdaterInterface;
use Ruwork\Synchronizer\IdExtractor\IdExtractorInterface;
use Ruwork\Synchronizer\Provider\ByIdProviderInterface;
use Ruwork\Synchronizer\Provider\ProviderInterface;

interface ConfigurationInterface
{
    public function getSourceProvider(): ?ProviderInterface;

    public function getSourceIdExtractor(): IdExtractorInterface;

    public function getSourceByIdProvider(): ?ByIdProviderInterface;

    public function getTargetProvider(): ?ProviderInterface;

    public function getTargetIdExtractor(): IdExtractorInterface;

    public function getTargetByIdProvider(): ?ByIdProviderInterface;

    public function getTargetCreator(): CreatorInterface;

    public function getTargetUpdater(): UpdaterInterface;

    public function getTargetDeleter(): DeleterInterface;
}
