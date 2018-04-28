<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

interface SynchronizerFactoryInterface
{
    public function createSynchronizer(string $type, array $attributes = []): SynchronizerInterface;

    public function createContextBuilder(): ContextBuilderInterface;
}
