<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\Wizard\Step\Factory\StepFactory;
use Ruwork\Wizard\Step\Factory\StepFactoryInterface;
use Ruwork\Wizard\Step\Type\BaseStepType;
use Ruwork\Wizard\Step\Type\SymfonyFormStepType;
use Ruwork\Wizard\Step\Type\SymfonyValidatorStepType;
use Ruwork\Wizard\Step\TypeResolver\StepTypeResolver;
use Ruwork\Wizard\Step\TypeResolver\StepTypeResolverInterface;
use Ruwork\Wizard\Wizard\Factory\WizardFactory;
use Ruwork\Wizard\Wizard\Factory\WizardFactoryInterface;
use Ruwork\Wizard\Wizard\TypeResolver\WizardTypeResolver;
use Ruwork\Wizard\Wizard\TypeResolver\WizardTypeResolverInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    // Step\Factory

    $services
        ->set(StepFactory::class)
        ->args([
            '$typeResolver' => ref(StepTypeResolverInterface::class),
        ]);

    $services->alias(StepFactoryInterface::class, StepFactory::class);

    // Step\Type

    $services
        ->set(BaseStepType::class)
        ->tag('ruwork_wizard.step_type');

    $services
        ->set(SymfonyFormStepType::class)
        ->args([
            '$formFactory' => ref('form.factory'),
        ])
        ->tag('ruwork_wizard.step_type');

    $services
        ->set(SymfonyValidatorStepType::class)
        ->args([
            '$validator' => ref('validator'),
        ])
        ->tag('ruwork_wizard.step_type');

    // Step\TypeResolver

    $services->set(StepTypeResolver::class);

    $services->alias(StepTypeResolverInterface::class, StepTypeResolver::class);

    // Wizard\Factory

    $services
        ->set(WizardFactory::class)
        ->args([
            '$typeResolver' => ref(WizardTypeResolverInterface::class),
            '$stepFactory' => ref(StepFactoryInterface::class),
        ]);

    $services->alias(WizardFactoryInterface::class, WizardFactory::class);

    // Wizard\TypeResolver

    $services->set(WizardTypeResolver::class);

    $services->alias(WizardTypeResolverInterface::class, WizardTypeResolver::class);
};
