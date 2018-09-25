<?php

declare(strict_types=1);

namespace Ruwork\Filter\Filter;

interface FilterInterface
{
    public function filter(&$object, $data): void;
}
