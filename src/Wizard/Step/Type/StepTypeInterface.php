<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Type;

use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface StepTypeInterface
{
    /**
     * @return string[]
     */
    public static function getRequiredTypes(): iterable;

    public function configureOptions(OptionsResolver $resolver): void;

    public function configureStep(StepConfiguratorInterface $configurator, array $options): void;
}
