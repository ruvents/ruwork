<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Marker;

interface MarkerInterface
{
    public function isMarked(string $id): bool;

    public function mark(string $id): void;

    public function unmark(string $id): void;
}
