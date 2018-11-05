<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\TypeResolver;

interface StepTypeResolverInterface
{
    public function resolve(string $type): ResolvedStepTypeInterface;
}
