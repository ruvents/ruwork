<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\TypeResolver;

use Ruwork\Wizard\Wizard\Builder\WizardConfiguratorInterface;
use Ruwork\Wizard\Wizard\Type\WizardTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ResolvedWizardTypeInterface
{
    public function getName(): string;

    public function getType(): WizardTypeInterface;

    /**
     * @return self[]
     */
    public function getRequiredTypes(): array;

    public function getOptionsResolver(): OptionsResolver;

    public function configureWizard(WizardConfiguratorInterface $configurator, array $options): void;
}
