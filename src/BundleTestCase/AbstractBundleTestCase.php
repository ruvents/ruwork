<?php

declare(strict_types=1);

namespace Ruwork\BundleTestCase;

use PHPUnit\Framework\TestCase;
use Ruwork\BundleTestCase\DependencyInjection\Compiler\ExposePrivateServicesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

abstract class AbstractBundleTestCase extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var string
     */
    private $extensionAlias;

    private $exposedServices = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->container->setParameter('container.autowiring.strict_mode', true);

        $bundle = $this->getBundle();
        $bundle->build($this->container);
        $this->build($this->container);

        $extension = $bundle->getContainerExtension();
        $this->container->registerExtension($extension);
        $this->extensionAlias = $extension->getAlias();
    }

    protected function tearDown(): void
    {
        $this->container = null;
    }

    abstract protected function getBundle(): BundleInterface;

    protected function build(ContainerBuilder $container): void
    {
    }

    protected function exposeService(string $id): void
    {
        if ($this->container->isCompiled()) {
            throw new \LogicException('Container is compiled.');
        }

        $this->exposedServices[] = $id;
    }

    protected function compile(array $config = []): void
    {
        $pass = new ExposePrivateServicesPass($this->exposedServices);
        $this->container->addCompilerPass($pass, PassConfig::TYPE_BEFORE_OPTIMIZATION, -10000);
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

    protected function assertContainerCompiles(ContainerBuilder $container)
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
