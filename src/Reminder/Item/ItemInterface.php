<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Item;

interface ItemInterface
{
    public function getId(): string;

    public function getData();
}
