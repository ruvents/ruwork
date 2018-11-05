<?php

declare(strict_types=1);

namespace Ruwork\Wizard\FormFactory;

interface FormFactoryInterface
{
    public function create($data, callable $handler);
}
