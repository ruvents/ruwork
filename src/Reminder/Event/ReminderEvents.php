<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Event;

final class ReminderEvents
{
    private function __construct()
    {
    }

    public static function remind(string $provider): string
    {
        return "ruwork_reminder.$provider.remind";
    }
}
