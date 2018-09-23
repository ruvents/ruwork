<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\DependencyInjection;

use Ruwork\RuworkBundle\Serializer\Encoder\ExcelCsvEncoder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

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

        if (!\interface_exists(EncoderInterface::class)) {
            $container->removeDefinition(ExcelCsvEncoder::class);
        }
    }
}
