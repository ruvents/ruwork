<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\AnnotationTools\Factory\CachedMetadataFactory;
use Ruwork\DoctrineBehaviorsBundle\Author\SecurityTokenAuthorProvider;
use Ruwork\DoctrineBehaviorsBundle\AuthorIp\RequestAuthorIpProvider;
use Ruwork\DoctrineBehaviorsBundle\DoctrineListener\AuthorIpListener;
use Ruwork\DoctrineBehaviorsBundle\DoctrineListener\AuthorListener;
use Ruwork\DoctrineBehaviorsBundle\DoctrineListener\MultilingualListener;
use Ruwork\DoctrineBehaviorsBundle\DoctrineListener\PersistTimestampListener;
use Ruwork\DoctrineBehaviorsBundle\DoctrineListener\SearchIndexListener;
use Ruwork\DoctrineBehaviorsBundle\DoctrineListener\UpdateTimestampListener;
use Ruwork\DoctrineBehaviorsBundle\EventListener\MultilingualRequestListener;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactory;

return function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set('cache.ruwork_doctrine_behaviors')
        ->parent('cache.system')
        ->private()
        ->tag('cache.pool');

    $services = $container->services()
        ->defaults()
        ->private();

    $services->set('ruwork_doctrine_behaviors.metadata_factory')
        ->class(MetadataFactory::class)
        ->args([
            '$reader' => ref('annotation_reader'),
        ]);

    $services->set('ruwork_doctrine_behaviors.cached_metadata_factory')
        ->class(CachedMetadataFactory::class)
        ->decorate('ruwork_doctrine_behaviors.metadata_factory')
        ->args([
            '$factory' => ref('ruwork_doctrine_behaviors.cached_metadata_factory.inner'),
            '$cache' => ref('cache.ruwork_doctrine_behaviors'),
        ]);

    $services->set('ruwork_doctrine_behaviors.security_token_author_provider')
        ->class(SecurityTokenAuthorProvider::class)
        ->args([
            '$tokenStorage' => ref('security.token_storage'),
        ]);

    $services->set('ruwork_doctrine_behaviors.author_listener')
        ->class(AuthorListener::class)
        ->args([
            '$metadataFactory' => ref('ruwork_doctrine_behaviors.metadata_factory'),
            '$provider' => ref('ruwork_doctrine_behaviors.security_token_author_provider'),
        ])
        ->tag('doctrine.event_subscriber');

    $services->set('ruwork_doctrine_behaviors.request_author_ip_provider')
        ->class(RequestAuthorIpProvider::class)
        ->args([
            '$requestStack' => ref('request_stack'),
        ]);

    $services->set('ruwork_doctrine_behaviors.author_ip_listener')
        ->class(AuthorIpListener::class)
        ->args([
            '$metadataFactory' => ref('ruwork_doctrine_behaviors.metadata_factory'),
            '$provider' => ref('ruwork_doctrine_behaviors.request_author_ip_provider'),
        ])
        ->tag('doctrine.event_subscriber');

    $services->set('ruwork_doctrine_behaviors.persist_timestamp_listener')
        ->class(PersistTimestampListener::class)
        ->args([
            '$metadataFactory' => ref('ruwork_doctrine_behaviors.metadata_factory'),
        ])
        ->tag('doctrine.event_subscriber');

    $services->set('ruwork_doctrine_behaviors.update_timestamp_listener')
        ->class(UpdateTimestampListener::class)
        ->args([
            '$metadataFactory' => ref('ruwork_doctrine_behaviors.metadata_factory'),
        ])
        ->tag('doctrine.event_subscriber');

    $services->set('ruwork_doctrine_behaviors.multilingual_request_listener')
        ->class(MultilingualRequestListener::class)
        ->args([
            '$defaultLocale' => '%kernel.default_locale%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set('ruwork_doctrine_behaviors.multilingual_listener')
        ->class(MultilingualListener::class)
        ->args([
            '$metadataFactory' => ref('ruwork_doctrine_behaviors.metadata_factory'),
            '$requestListener' => ref('ruwork_doctrine_behaviors.multilingual_request_listener'),
        ])
        ->tag('doctrine.event_subscriber');

    $services->set('ruwork_doctrine_behaviors.search_index_listener')
        ->class(SearchIndexListener::class)
        ->args([
            '$metadataFactory' => ref('ruwork_doctrine_behaviors.metadata_factory'),
            '$accessor' => ref('property_accessor'),
        ])
        ->tag('doctrine.event_subscriber');
};
