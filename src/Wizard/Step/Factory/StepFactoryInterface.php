<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Factory;

use Ruwork\Wizard\Step\StepInterface;

interface StepFactoryInterface
{
    public function create(string $name, string $type, array $options = []): StepInterface;
}
