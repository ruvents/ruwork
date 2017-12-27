<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\WizardBundle\EventListener\StepNotFoundExceptionListener;
use Ruwork\WizardBundle\Storage\SessionStorage;
use Ruwork\WizardBundle\Storage\StorageInterface;
use Ruwork\WizardBundle\Type\TypeFacadeFactory;
use Ruwork\WizardBundle\WizardFactory;
use Ruwork\WizardBundle\WizardFactoryInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(TypeFacadeFactory::class)
        ->args([
            '$normalizer' => ref('serializer'),
            '$denormalizer' => ref('serializer'),
            '$validator' => ref('validator'),
            '$formFactory' => ref('form.factory'),
        ]);

    $services->set(SessionStorage::class)
        ->args([
            '$session' => ref('session'),
        ]);

    $services->alias(StorageInterface::class, SessionStorage::class);

    $services->set(WizardFactory::class)
        ->args([
            '$typeFacadeFactory' => ref(TypeFacadeFactory::class),
            '$storage' => ref(StorageInterface::class),
        ]);

    $services->alias(WizardFactoryInterface::class, WizardFactory::class);

    $services->set(StepNotFoundExceptionListener::class)
        ->tag('kernel.event_subscriber');
};
