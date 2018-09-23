<?php

declare(strict_types=1);

namespace Ruwork\Filter\Builder;

use Ruwork\Filter\Filter\FilterInterface;

interface FilterBuilderInterface
{
    public function getOptions(): array;

    public function getOption(string $name, $default = null);

    public function add(callable $filter, ?string $path = null, int $priority = 0): self;

    public function embed(string $type, array $options = [], ?string $path = null, int $priority = 0): self;

    public function getFilter(): FilterInterface;
}
