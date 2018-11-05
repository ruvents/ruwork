<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\Factory;

use Ruwork\Wizard\Step\Factory\StepFactoryInterface;
use Ruwork\Wizard\Wizard\Builder\WizardBuilder;
use Ruwork\Wizard\Wizard\TypeResolver\WizardTypeResolverInterface;
use Ruwork\Wizard\Wizard\WizardInterface;

final class WizardFactory implements WizardFactoryInterface
{
    private $typeResolver;
    private $stepFactory;

    public function __construct(
        WizardTypeResolverInterface $typeResolver,
        StepFactoryInterface $stepFactory = null
    ) {
        $this->typeResolver = $typeResolver;
        $this->stepFactory = $stepFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type, $data = null, array $options = []): WizardInterface
    {
        $type = $this->typeResolver->resolve($type);
        $resolver = clone $type->getOptionsResolver();
        $resolver->setDefined('data');

        if (null !== $data) {
            $options['data'] = $data;
        }

        $options = $resolver->resolve($options);
        $wizardBuilder = new WizardBuilder($this->stepFactory, $options, $options['data']);
        $type->configureWizard($wizardBuilder, $options);

        return $wizardBuilder->build();
    }
}
