<?php

declare(strict_types=1);

namespace Ruwork\PaginatorBundle;

use Ruwork\BundleTestCase\AbstractBundleTestCase;
use Ruwork\PaginatorBundle\EventListener\PageOutOfRangeExceptionListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Twig\Loader\FilesystemLoader;

class RuworkPaginatorBundleTest extends AbstractBundleTestCase
{
    public function testUnobtrusiveCompiler(): void
    {
        $this->assertContainerCompiles();
    }

    public function testBundle(): void
    {
        $this->registerService('twig.loader.native_filesystem', FilesystemLoader::class)
            ->setPublic(true);

        $this->loadBundleExtension();
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithTag(PageOutOfRangeExceptionListener::class, 'kernel.event_subscriber');

        /** @var FilesystemLoader $loader */
        $loader = $this->container->get('twig.loader.native_filesystem');
        $this->assertTrue($loader->exists('@RuworkPaginator/bootstrap_4.html.twig'));
    }

    protected function setUpContainer(ContainerBuilder $container): void
    {
        $this->exposeService(PageOutOfRangeExceptionListener::class);
    }

    protected function getBundle(): BundleInterface
    {
        return new RuworkPaginatorBundle();
    }
}
