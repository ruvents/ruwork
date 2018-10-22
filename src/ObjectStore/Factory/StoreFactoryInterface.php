<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Factory;

use Ruwork\ObjectStore\StoreInterface;

interface StoreFactoryInterface
{
    public function create(string $type, array $options = []): StoreInterface;
}
