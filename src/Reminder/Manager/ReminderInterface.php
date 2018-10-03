<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Manager;

interface ReminderInterface
{
    public function remind(string $providerName, ?\DateTimeImmutable $now = null): void;
}
