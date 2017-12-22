<?php

declare(strict_types=1);

namespace Ruwork\Paginator;

final class Page
{
    private $number;
    private $current;

    public function __construct(int $number, bool $current)
    {
        $this->number = $number;
        $this->current = $current;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function isFirst(): bool
    {
        return 1 === $this->number;
    }

    public function isCurrent(): bool
    {
        return $this->current;
    }
}
