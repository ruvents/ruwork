<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\DependencyInjection;

use Ruwork\RuworkBundle\Serializer\Encoder\ExcelCsvEncoder;
use Ruwork\RuworkBundle\Serializer\Normalizer\DoctrineObjectNormalizer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Serializer\SerializerInterface;

final class RuworkExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        if (!\interface_exists(SerializerInterface::class)) {
            $container->removeDefinition(DoctrineObjectNormalizer::class);
            $container->removeDefinition(ExcelCsvEncoder::class);
        }
    }
}
