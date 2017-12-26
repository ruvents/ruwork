<?php

declare(strict_types=1);

namespace Ruwork\BundleTestCase;

use PHPUnit\Framework\TestCase;
use Ruwork\BundleTestCase\DependencyInjection\Compiler\ExposeServicesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractBundleTestCase extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

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

    protected function registerService(string $id, string $class = null): Definition
    {
        return $this->container->register($id, $class);
    }

    protected function setParameter(string $name, $value): void
    {
        $this->container->setParameter($name, $value);
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

    protected function compile(): void
    {
        $this->container->compile();
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

    protected function assertContainerHasService(string $id): void
    {
        $this->assertTrue($this->container->has($id), sprintf('Container does not have a service "%s".', $id));
    }

    protected function assertContainerNotHasService(string $id): void
    {
        $this->assertFalse($this->container->has($id), sprintf('Container has a service "%s".', $id));
    }
}
