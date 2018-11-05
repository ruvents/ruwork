<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Type;

use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BaseStepType extends AbstractStepType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'path' => null,
                'position' => 0,
            ])
            ->setAllowedTypes('path', ['null', 'string'])
            ->setAllowedTypes('position', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function configureStep(StepConfiguratorInterface $configurator, array $options): void
    {
        $configurator
            ->setPath($options['path'])
            ->setPosition($options['position']);
    }
}
