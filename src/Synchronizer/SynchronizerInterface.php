<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

interface SynchronizerInterface
{
    public function syncAll(): void;

    public function syncAndYieldAll(): \Generator;

    public function syncTarget($target);

    public function syncSource($source, bool $lazy = false);

    public function syncById($id, bool $lazy = false);

    public function clearCache(): void;
}
