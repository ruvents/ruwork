<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\Synchronizer\Cache\NamespacedCacheFactory;
use Ruwork\Synchronizer\Event\Doctrine\FlushListener;
use Ruwork\Synchronizer\SynchronizerFactory;
use Ruwork\Synchronizer\SynchronizerFactoryInterface;
use Symfony\Component\Cache\Simple\Psr6Cache;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('ruwork_synchronizer.cache', Psr6Cache::class)
        ->arg('$pool', ref('cache.app'));

    $services->set('ruwork_synchronizer.namespaced_cache_factory', NamespacedCacheFactory::class)
        ->arg('$cache', ref('ruwork_synchronizer.cache'));

    $services->set('ruwork_synchronizer.factory', SynchronizerFactory::class)
        ->arg('$cacheFactory', ref('ruwork_synchronizer.namespaced_cache_factory'));

    $services->alias(SynchronizerFactoryInterface::class, 'ruwork_synchronizer.factory');

    $services->set(FlushListener::class)
        ->arg('$entityManager', ref('doctrine.orm.default_entity_manager'));
};
