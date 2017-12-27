<?php

declare(strict_types=1);

namespace Ruwork\BundleTest;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;
use Ruwork\BundleTest\DependencyInjection\Compiler\ExposeServicesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractBundleTestCase extends AbstractContainerBuilderTestCase
{
    /**
     * @var BundleInterface
     */
    protected $bundle;

    /**
     * @var ExposeServicesPass
     */
    private $exposeServicesPass;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->exposeServicesPass = new ExposeServicesPass();

        $this->container
            ->addCompilerPass($this->exposeServicesPass, PassConfig::TYPE_BEFORE_OPTIMIZATION, -1000)
            ->setParameter('container.autowiring.strict_mode', true);

        $this->bundle = $this->getBundle();
        $this->bundle->build($this->container);
        $this->setUpContainer($this->container);
    }

    protected function tearDown(): void
    {
        $this->container = null;
        $this->bundle = null;
        $this->exposeServicesPass = null;
    }

    abstract protected function getBundle(): BundleInterface;

    protected function setUpContainer(ContainerBuilder $container): void
    {
    }

    protected function registerService($id, $class = null): Definition
    {
        return $this->container->register($id, $class);
    }

    protected function exposeService(string $id): void
    {
        $this->exposeServicesPass->addService($id);
    }

    protected function loadBundleExtension(array $config = []): void
    {
        $extension = $this->bundle->getContainerExtension();
        $this->container->registerExtension($extension);
        $this->container->loadFromExtension($extension->getAlias(), $config);
    }

    protected function assertContainerCompiles(): void
    {
        try {
            $this->container->compile();
            $this->assertTrue(true);
        } catch (\Throwable $exception) {
            $this->fail('Failed to compile container.');
        }
    }
}
