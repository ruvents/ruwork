<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Item;

interface ClearableItemInterface extends ItemInterface
{
    public function clear(): void;
}
