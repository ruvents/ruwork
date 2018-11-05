<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\Factory;

use Ruwork\Wizard\Wizard\WizardInterface;

interface WizardFactoryInterface
{
    public function create(string $type, $data = null, array $options = []): WizardInterface;
}
