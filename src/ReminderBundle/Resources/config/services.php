<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\Reminder\Command\RemindCommand;
use Ruwork\Reminder\Manager\Reminder;
use Ruwork\Reminder\Manager\ReminderInterface;
use Ruwork\Reminder\Marker\DatabaseMarker;
use Ruwork\Reminder\Marker\MarkerInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    // Command

    $services
        ->set(RemindCommand::class)
        ->args([
            '$reminder' => ref(ReminderInterface::class),
        ])
        ->tag('console.command');

    // Manager

    $services
        ->set(Reminder::class)
        ->args([
            '$dispatcher' => ref('event_dispatcher'),
            '$marker' => ref(MarkerInterface::class),
        ]);

    $services->alias(ReminderInterface::class, Reminder::class);

    // Marker

    $services
        ->set(DatabaseMarker::class.'.abstract')
        ->class(DatabaseMarker::class)
        ->abstract();
};
