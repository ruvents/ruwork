<?php

declare(strict_types=1);

namespace Ruwork\SynchronizerBundle\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Ruwork\Synchronizer\Event\Doctrine\FlushListener;
use Ruwork\Synchronizer\Type\TypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class RuworkSynchronizerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->registerForAutoconfiguration(TypeInterface::class)
            ->setPublic(false)
            ->addTag('ruwork_synchronizer.synchronization_type');

        if (!\class_exists(DoctrineBundle::class)) {
            $container->removeDefinition(FlushListener::class);
        }
    }
}
