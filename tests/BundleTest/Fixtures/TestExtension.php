<?php

declare(strict_types=1);

namespace Ruwork\BundleTest\Fixtures;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class TestExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->setParameter('test_extension_configs', $configs);
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function getXsdValidationBasePath(): string
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'test';
    }
}
