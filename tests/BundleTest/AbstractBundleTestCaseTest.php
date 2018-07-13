<?php

declare(strict_types=1);

namespace Ruwork\BundleTest;

use PHPUnit\Framework\AssertionFailedError;
use Ruwork\BundleTest\Fixtures\TestBundle;
use Ruwork\BundleTest\Fixtures\TestService;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @internal
 */
class AbstractBundleTestCaseTest extends AbstractBundleTestCase
{
    public function testExposeService(): void
    {
        $this->registerService(TestService::class)
            ->setPublic(false);

        $this->container->setAlias('alias', new Alias(TestService::class, false));

        // non existing services must not throw
        $this->exposeService('non_existing_service');
        $this->exposeService(TestService::class);
        $this->exposeService('alias');

        $this->assertContainerCompiles();
        $this->assertTrue($this->container->findDefinition(TestService::class)->isPublic());
        $this->assertTrue($this->container->getAlias('alias')->isPublic());
    }

    public function testContainerCompilesFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed to compile container.');

        $this->registerService('abc');
        $this->assertContainerCompiles();
    }

    public function testExtensionDoesNotLoadItself(): void
    {
        $this->compile();
        $this->assertFalse($this->container->has('test_bundle_parameter'));
    }

    public function testNoExtensionException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(\sprintf('Bundle "%s" does not have an extension.', \get_class($this->getBundle())));

        $this->loadBundleExtension([]);
        $this->compile();
    }

    public function testBundleBuildCall(): void
    {
        $this->assertContainerBuilderHasParameter('test_bundle_parameter');
    }

    public function testSetUpContainerCall(): void
    {
        $this->assertContainerBuilderHasParameter('test_set_up_container_parameter');
    }

    public function testExtensionLoading(): void
    {
        $this->bundle = new TestBundle(true);
        $this->loadBundleExtension($expected = [1, 'a' => 2]);

        $this->assertContainerCompiles();
        $this->assertSame([$expected], $this->container->getParameter('test_extension_configs'));
    }

    protected function setUpContainer(ContainerBuilder $container): void
    {
        parent::setUpContainer($container);
        $container->setParameter('test_set_up_container_parameter', true);
    }

    protected function getBundle(): BundleInterface
    {
        return new TestBundle();
    }
}
