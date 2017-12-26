<?php

declare(strict_types=1);

namespace Ruwork\BundleTestCase;

use PHPUnit\Framework\TestCase;
use Ruwork\BundleTestCase\DependencyInjection\Compiler\ExposeServicesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractBundleTestCase extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $extensionAlias;

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

        $bundle = $this->getBundle();
        $bundle->build($this->container);
        $this->setUpContainer($this->container);

        $extension = $bundle->getContainerExtension();
        $this->container->registerExtension($extension);
        $this->extensionAlias = $extension->getAlias();
    }

    protected function tearDown(): void
    {
        $this->container = null;
        $this->extensionAlias = null;
        $this->exposeServicesPass = null;
    }

    abstract protected function getBundle(): BundleInterface;

    protected function setUpContainer(ContainerBuilder $container): void
    {
    }

    protected function exposeService(string $id): void
    {
        $this->exposeServicesPass->addService($id);
    }

    protected function compile(array $config = []): void
    {
        $this->container->loadFromExtension($this->extensionAlias, $config);
        $this->container->compile();
    }

    protected function getContainer(): ContainerInterface
    {
        if (!$this->container->isCompiled()) {
            $this->compile();
        }

        return $this->container;
    }

    protected function assertContainerCompiles(ContainerBuilder $container): void
    {
        try {
            $container->compile();
            $this->assertTrue(true);
        } catch (\Throwable $exception) {
            $this->fail('Failed to compile container.');
        }
    }

    protected function assertContainerHasService(string $id): void
    {
        $this->assertTrue($this->getContainer()->has($id), sprintf('Container does not have a service "%s".', $id));
    }

    protected function assertContainerNotHasService(string $id): void
    {
        $this->assertFalse($this->getContainer()->has($id), sprintf('Container has service "%s".', $id));
    }
}
