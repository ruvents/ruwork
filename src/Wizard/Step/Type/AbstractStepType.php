<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Type;

use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractStepType implements StepTypeInterface
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
    public function configureStep(StepConfiguratorInterface $configurator, array $options): void
    {
    }
}
