<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event;

class PreSyncEvent extends SyncEvent
{
    private $skipped = false;

    public function isSkipped(): bool
    {
        return $this->skipped;
    }

    public function skip(): void
    {
        $this->skipped = true;
        $this->stopPropagation();
    }
}
