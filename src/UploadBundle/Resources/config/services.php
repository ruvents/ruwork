<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\UploadBundle\Doctrine\EventListener\UploadListener;
use Ruwork\UploadBundle\Form\Saver\Saver;
use Ruwork\UploadBundle\Form\Saver\SaverCollectorInterface;
use Ruwork\UploadBundle\Form\Saver\SaverInterface;
use Ruwork\UploadBundle\Form\Type\DoctrineUploadType;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Ruwork\UploadBundle\Form\TypeGuesser\DoctrineUploadTypeGuesser;
use Ruwork\UploadBundle\Manager\UploadManager;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Ruwork\UploadBundle\Metadata\CachedMetadataFactory;
use Ruwork\UploadBundle\Metadata\MetadataFactory;
use Ruwork\UploadBundle\Metadata\MetadataFactoryInterface;
use Ruwork\UploadBundle\Metadata\UnproxyMetadataFactory;
use Ruwork\UploadBundle\Metadata\UploadAccessor;
use Ruwork\UploadBundle\Path\PathGenerator;
use Ruwork\UploadBundle\Path\PathGeneratorInterface;
use Ruwork\UploadBundle\Path\PathLocatorInterface;
use Ruwork\UploadBundle\Source\Handler\UploadedFileHandler;
use Ruwork\UploadBundle\Source\SourceResolver;
use Ruwork\UploadBundle\Source\SourceResolverInterface;
use Ruwork\UploadBundle\TmpPath\TmpPathGenerator;
use Ruwork\UploadBundle\TmpPath\TmpPathGeneratorInterface;
use Ruwork\UploadBundle\Validator\AssertUploadValidator;
use Symfony\Component\HttpKernel\KernelEvents;

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
            '$manager' => ref(UploadManagerInterface::class),
        ])
        ->tag('doctrine.event_subscriber');

    // Form\Saver

    $services
        ->set(Saver::class)
        ->tag('kernel.event_listener', [
            'event' => KernelEvents::RESPONSE,
            'method' => 'save',
        ]);

    $services->alias(SaverCollectorInterface::class, Saver::class);

    $services->alias(SaverInterface::class, Saver::class);

    // Form\Type

    $services
        ->set(DoctrineUploadType::class)
        ->args([
            '$doctrine' => ref('doctrine'),
            '$metadataFactory' => ref(MetadataFactoryInterface::class),
        ])
        ->tag('form.type');

    $services
        ->set(UploadType::class)
        ->args([
            '$manager' => ref(UploadManagerInterface::class),
            '$savers' => ref(SaverCollectorInterface::class),
        ])
        ->tag('form.type');

    // Form\TypeGuesser

    $services
        ->set(DoctrineUploadTypeGuesser::class)
        ->args([
            '$doctrine' => ref('doctrine'),
            '$metadataFactory' => ref(MetadataFactoryInterface::class),
        ])
        ->tag('form.type_guesser', ['priority' => 256]);

    // Manager

    $services
        ->set(UploadManager::class)
        ->args([
            '$sourceResolver' => ref(SourceResolverInterface::class),
            '$accessor' => ref(UploadAccessor::class),
            '$pathLocator' => ref(PathLocatorInterface::class),
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

    // Path

    $services
        ->set(PathGenerator::class)
        ->args([
            '$uploadsDir' => '%ruwork_upload.uploads_dir%',
        ]);

    $services->alias(PathGeneratorInterface::class, PathGenerator::class);

    $services->alias(PathLocatorInterface::class, PathGenerator::class);

    // Source\Handler

    $services
        ->set(UploadedFileHandler::class)
        ->tag('ruwork_upload.source_handler');

    // Source

    $services
        ->set(SourceResolver::class)
        ->args([
            '$handlers' => tagged('ruwork_upload.source_handler'),
            '$tmpPathGenerator' => ref(TmpPathGeneratorInterface::class),
            '$pathGenerator' => ref(PathGeneratorInterface::class),
            '$pathLocator' => ref(PathLocatorInterface::class),
        ]);

    $services->alias(SourceResolverInterface::class, SourceResolver::class);

    // TmpPath

    $services->set(TmpPathGenerator::class);

    $services->alias(TmpPathGeneratorInterface::class, TmpPathGenerator::class);

    // Validator

    $services
        ->set(AssertUploadValidator::class)
        ->args([
            '$manager' => ref(UploadManagerInterface::class),
        ])
        ->tag('validator.constraint_validator');
};
