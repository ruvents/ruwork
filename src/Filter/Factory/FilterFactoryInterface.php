<?php

declare(strict_types=1);

namespace Ruwork\Filter\Factory;

use Ruwork\Filter\Builder\FilterBuilderInterface;
use Ruwork\Filter\Filter\FilterInterface;

interface FilterFactoryInterface
{
    public function createBuilder(?string $type = null, array $options = []): FilterBuilderInterface;

    public function create(string $type, array $options = []): FilterInterface;
}
