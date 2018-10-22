<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\ObjectStore\Factory\StoreFactory;
use Ruwork\ObjectStore\Factory\StoreFactoryInterface;
use Ruwork\ObjectStore\Type\BaseStoreType;
use Ruwork\ObjectStore\Type\NativeSessionStoreType;
use Ruwork\ObjectStore\Type\SymfonyNormalizerStoreType;
use Ruwork\ObjectStore\Type\SymfonySessionStoreType;
use Ruwork\ObjectStore\TypeResolver\StoreTypeResolver;
use Ruwork\ObjectStore\TypeResolver\StoreTypeResolverInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    // Factory

    $services
        ->set(StoreFactory::class)
        ->args([
            '$typeResolver' => ref(StoreTypeResolverInterface::class),
        ]);

    $services->alias(StoreFactoryInterface::class, StoreFactory::class);

    // Type

    $services
        ->set(BaseStoreType::class)
        ->tag('ruwork_object_store.type');

    $services
        ->set(NativeSessionStoreType::class)
        ->tag('ruwork_object_store.type');

    $services
        ->set(SymfonyNormalizerStoreType::class)
        ->args([
            '$normalizer' => ref('serializer'),
            '$denormalizer' => ref('serializer'),
        ])
        ->tag('ruwork_object_store.type');

    $services
        ->set(SymfonySessionStoreType::class)
        ->args([
            '$session' => ref('session'),
        ])
        ->tag('ruwork_object_store.type');

    // TypeResolver

    $services->set(StoreTypeResolver::class);

    $services->alias(StoreTypeResolverInterface::class, StoreTypeResolver::class);
};
