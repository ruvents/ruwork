<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event;

final class SyncEvents
{
    /**
     * @Event("Ruwork\Synchronizer\Event\PreSyncEvent")
     */
    public const PRE_CREATE = 'sync.pre_create';

    /**
     * @Event("Ruwork\Synchronizer\Event\SyncEvent")
     */
    public const POST_CREATE = 'sync.post_create';

    /**
     * @Event("Ruwork\Synchronizer\Event\PreSyncEvent")
     */
    public const PRE_UPDATE = 'sync.pre_update';

    /**
     * @Event("Ruwork\Synchronizer\Event\SyncEvent")
     */
    public const POST_UPDATE = 'sync.post_update';

    /**
     * @Event("Ruwork\Synchronizer\Event\PreSyncEvent")
     */
    public const PRE_DELETE = 'sync.pre_delete';

    /**
     * @Event("Ruwork\Synchronizer\Event\SyncEvent")
     */
    public const POST_DELETE = 'sync.post_delete';

    /**
     * @Event("Ruwork\Synchronizer\Event\ErrorEvent")
     */
    public const ON_ERROR = 'sync.on_error';

    /**
     * @Event("Ruwork\Synchronizer\Event\CompleteEvent")
     */
    public const ON_COMPLETE = 'sync.on_complete';

    private function __construct()
    {
    }
}
