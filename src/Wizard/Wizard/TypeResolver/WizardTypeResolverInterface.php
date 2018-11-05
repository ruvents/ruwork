<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\TypeResolver;

interface WizardTypeResolverInterface
{
    public function resolve(string $type): ResolvedWizardTypeInterface;
}
