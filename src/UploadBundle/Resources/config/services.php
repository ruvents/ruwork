<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\UploadBundle\Doctrine\EventListener\UploadListener;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Ruwork\UploadBundle\Form\TypeExtension\UploadFormTypeExtension;
use Ruwork\UploadBundle\Locator\UploadLocator;
use Ruwork\UploadBundle\Locator\UploadLocatorInterface;
use Ruwork\UploadBundle\Manager\UploadManager;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Ruwork\UploadBundle\Metadata\CachedMetadataFactory;
use Ruwork\UploadBundle\Metadata\MetadataFactory;
use Ruwork\UploadBundle\Metadata\MetadataFactoryInterface;
use Ruwork\UploadBundle\Metadata\UnproxyMetadataFactory;
use Ruwork\UploadBundle\Metadata\UploadAccessor;
use Ruwork\UploadBundle\PathGenerator\PathGenerator;
use Ruwork\UploadBundle\PathGenerator\PathGeneratorInterface;
use Ruwork\UploadBundle\Source\Handler\UploadedFileHandler;
use Ruwork\UploadBundle\Source\SourceResolver;
use Ruwork\UploadBundle\Source\SourceResolverInterface;
use Ruwork\UploadBundle\Validator\AssertUploadValidator;
use Symfony\Component\Form\Extension\Core\Type\FormType;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set('cache.ruwork_upload')
        ->parent('cache.system')
        ->private()
        ->tag('cache.pool');

    $services = $container->services()
        ->defaults()
        ->private();

    // Doctrine

    $services
        ->set(UploadListener::class)
        ->args([
            '$uploadManager' => ref(UploadManagerInterface::class),
        ])
        ->tag('doctrine.event_subscriber');

    // Form

    $services
        ->set(UploadType::class)
        ->args([
            '$manager' => ref(UploadManagerInterface::class),
            '$accessor' => ref(UploadAccessor::class),
        ])
        ->tag('form.type');

    $services
        ->set(UploadFormTypeExtension::class)
        ->tag('form.type_extension', ['extended_type' => FormType::class]);

    // Locator

    $services
        ->set(UploadLocator::class)
        ->args([
            '$accessor' => ref(UploadAccessor::class),
        ]);

    $services->alias(UploadLocatorInterface::class, UploadLocator::class);

    // Manager

    $services
        ->set(UploadManager::class)
        ->args([
            '$metadataFactory' => ref(MetadataFactoryInterface::class),
            '$sourceResolver' => ref(SourceResolverInterface::class),
            '$pathGenerator' => ref(PathGeneratorInterface::class),
            '$accessor' => ref(UploadAccessor::class),
            '$locator' => ref(UploadLocatorInterface::class),
        ]);

    $services->alias(UploadManagerInterface::class, UploadManager::class);

    // Metadata

    $services
        ->set(MetadataFactory::class)
        ->args([
            '$annotationsReader' => ref('annotation_reader'),
        ]);

    $services
        ->set(CachedMetadataFactory::class)
        ->decorate(MetadataFactory::class)
        ->args([
            '$factory' => ref(CachedMetadataFactory::class.'.inner'),
            '$cache' => ref('cache.ruwork_upload'),
            '$debug' => '%kernel.debug%',
        ]);

    $services
        ->set(UnproxyMetadataFactory::class)
        ->decorate(MetadataFactory::class, null, -100)
        ->args([
            '$factory' => ref(UnproxyMetadataFactory::class.'.inner'),
        ]);

    $services->alias(MetadataFactoryInterface::class, MetadataFactory::class);

    $services
        ->set(UploadAccessor::class)
        ->args([
            '$metadataFactory' => ref(MetadataFactoryInterface::class),
        ]);

    // PathGenerator

    $services
        ->set(PathGenerator::class)
        ->args([
            '$uploadsDir' => '%ruwork_upload.uploads_dir%',
        ]);

    $services->alias(PathGeneratorInterface::class, PathGenerator::class);

    // Source

    $services
        ->set(UploadedFileHandler::class)
        ->tag('ruwork_upload.source_handler');

    $services
        ->set(SourceResolver::class)
        ->args([
            '$handlers' => tagged('ruwork_upload.source_handler'),
        ]);

    $services->alias(SourceResolverInterface::class, SourceResolver::class);

    // Validator

    $services
        ->set(AssertUploadValidator::class)
        ->args([
            '$manager' => ref(UploadManagerInterface::class),
            '$locator' => ref(UploadLocatorInterface::class),
        ])
        ->tag('validator.constraint_validator');
};
