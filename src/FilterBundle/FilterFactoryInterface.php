<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

interface FilterFactoryInterface
{
    public function create(string $filterType, array $options = []): FilterInterface;
}
