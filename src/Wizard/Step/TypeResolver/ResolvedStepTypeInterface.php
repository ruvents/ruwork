<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\TypeResolver;

use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Ruwork\Wizard\Step\Type\StepTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ResolvedStepTypeInterface
{
    public function getName(): string;

    public function getType(): StepTypeInterface;

    /**
     * @return self[]
     */
    public function getRequiredTypes(): array;

    public function getOptionsResolver(): OptionsResolver;

    public function configureStep(StepConfiguratorInterface $configurator, array $options): void;
}
