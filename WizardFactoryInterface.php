<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle;

use Ruwork\WizardBundle\Type\WizardBuilder;

interface WizardFactoryInterface
{
    public function createWizardBuilder(string $type, $data = null, array $options = []): WizardBuilder;

    public function createWizard(string $type, $data = null, array $options = []): Wizard;
}
