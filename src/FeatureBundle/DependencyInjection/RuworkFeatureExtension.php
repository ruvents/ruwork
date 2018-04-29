<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle\DependencyInjection;

use Ruwork\FeatureBundle\FeatureInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class RuworkFeatureExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->registerForAutoconfiguration(FeatureInterface::class)
            ->setPublic(false)
            ->addTag('ruwork_feature');
    }
}
