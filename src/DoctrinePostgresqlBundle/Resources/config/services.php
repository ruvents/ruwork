<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\DoctrinePostgresqlBundle\DependencyInjection\RuworkDoctrinePostgresqlExtension as DI;
use Ruwork\DoctrinePostgresqlBundle\EventListener\FixDefaultSchemaListener;
use Ruwork\DoctrinePostgresqlBundle\EventListener\TextSearchIndexListener;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(DI::LISTENER.'fix_default_schema', FixDefaultSchemaListener::class)
        ->abstract();

    $services->set(DI::LISTENER.'text_search_index', TextSearchIndexListener::class)
        ->abstract();
};
