<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\DoctrineBehaviorsBundle\DependencyInjection\RuworkDoctrineBehaviorsExtension as DI;
use Ruwork\DoctrineBehaviorsBundle\EventListener\AuthorListener;
use Ruwork\DoctrineBehaviorsBundle\EventListener\MultilingualListener;
use Ruwork\DoctrineBehaviorsBundle\EventListener\PersistTimestampListener;
use Ruwork\DoctrineBehaviorsBundle\EventListener\SearchColumnListener;
use Ruwork\DoctrineBehaviorsBundle\EventListener\UpdateTimestampListener;
use Ruwork\DoctrineBehaviorsBundle\Metadata\LazyLoadingMetadataFactory;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactory;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Search\SearchManager;
use Ruwork\DoctrineBehaviorsBundle\Strategy\AuthorStrategy\SecurityTokenAuthorStrategy;
use Ruwork\DoctrineBehaviorsBundle\Strategy\TimestampStrategy\FieldTypeTimestampStrategy;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set($cacheId = 'cache.ruwork_doctrine_behaviors')
        ->parent('cache.system')
        ->private()
        ->tag('cache.pool');

    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(MetadataFactory::class)
        ->args([
            '$annotationReader' => ref('annotation_reader'),
        ]);

    $services->set(LazyLoadingMetadataFactory::class)
        ->args([
            '$factory' => ref(MetadataFactory::class),
            '$cache' => ref($cacheId),
        ]);

    $services->alias(MetadataFactoryInterface::class, LazyLoadingMetadataFactory::class);

    $services->set(SecurityTokenAuthorStrategy::class)
        ->args([
            '$tokenStorage' => ref('security.token_storage'),
        ]);

    $services->set(FieldTypeTimestampStrategy::class);

    $services->set(DI::LISTENER.'search_column', SearchColumnListener::class)
        ->abstract()
        ->args([
            '$factory' => ref(MetadataFactoryInterface::class),
        ]);

    $services->set(DI::LISTENER.'author', AuthorListener::class)
        ->abstract()
        ->args([
            '$factory' => ref(MetadataFactoryInterface::class),
        ]);

    $services->set(DI::LISTENER.'multilingual', MultilingualListener::class)
        ->abstract()
        ->args([
            '$factory' => ref(MetadataFactoryInterface::class),
            '$requestStack' => ref('request_stack'),
        ]);

    $services->set(DI::LISTENER.'persist_timestamp', PersistTimestampListener::class)
        ->abstract()
        ->args([
            '$factory' => ref(MetadataFactoryInterface::class),
        ]);

    $services->set(DI::LISTENER.'update_timestamp', UpdateTimestampListener::class)
        ->abstract()
        ->args([
            '$factory' => ref(MetadataFactoryInterface::class),
        ]);

    $services->set(SearchManager::class)
        ->args([
            '$doctrine' => ref('doctrine'),
            '$factory' => ref(MetadataFactoryInterface::class),
            '$accessor' => ref('property_accessor'),
        ]);
};
