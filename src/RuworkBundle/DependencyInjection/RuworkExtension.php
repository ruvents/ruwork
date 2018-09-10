<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\DependencyInjection;

use Ruwork\RuworkBundle\Mailer\Mailer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

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

        $container
            ->findDefinition(Mailer::class)
            ->setArgument('$users', $config['mailer']['users']);
    }
}
