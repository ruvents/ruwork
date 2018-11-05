<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\Type;

use Ruwork\Wizard\Wizard\Builder\WizardConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface WizardTypeInterface
{
    /**
     * @return string[]
     */
    public static function getRequiredTypes(): iterable;

    public function configureOptions(OptionsResolver $resolver): void;

    public function configureWizard(WizardConfiguratorInterface $configurator, array $options): void;
}
