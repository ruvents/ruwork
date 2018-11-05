<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\Type;

use Ruwork\Wizard\Wizard\Builder\WizardConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractWizardType implements WizardTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getRequiredTypes(): iterable
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureWizard(WizardConfiguratorInterface $configurator, array $options): void
    {
    }
}
