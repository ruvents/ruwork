<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection;

use Ruwork\UploadBundle\Path\PathLocator;
use Ruwork\UploadBundle\Source\Handler\SourceHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class RuworkUploadExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        $container->setParameter('ruwork_upload.uploads_dir', $config['uploads_dir']);

        $container->findDefinition(PathLocator::class)
            ->setArgument('$publicDir', $config['public_dir']);

        $container->registerForAutoconfiguration(SourceHandlerInterface::class)
            ->addTag('ruwork_upload.source_handler');
    }
}
