<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Provider;

use Ruwork\Reminder\Item\ItemInterface;

interface ProviderInterface
{
    public static function getName(): string;

    /**
     * @return ItemInterface[]
     */
    public function getItems(\DateTimeImmutable $time): iterable;
}
