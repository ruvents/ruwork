<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle;

use Ruwork\BundleTest\AbstractBundleTestCase;
use Ruwork\RouteOptionalPrefix\LoaderDecorator;
use Ruwork\RouteOptionalPrefix\RouterDecorator;
use Ruwork\RoutingToolsBundle\Twig\BridgeRoutingExtension;
use Ruwork\RoutingToolsBundle\Twig\RoutingHelpersExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Router;

/**
 * @internal
 */
class RuworkRoutingToolsBundleTest extends AbstractBundleTestCase
{
    public function testUnobtrusiveCompilerPasses(): void
    {
        $this->assertContainerCompiles();
    }

    public function testOptionalPrefixDefault(): void
    {
        $this->loadBundleExtension();
        $this->compile();

        $this->assertInstanceOf(Router::class, $this->container->get('router'));
        $this->assertInstanceOf(PhpFileLoader::class, $this->container->get('routing.loader'));
        $this->assertSame(BridgeRoutingExtension::class, \get_class($this->container->get('twig.extension.routing')));
        $this->assertContainerBuilderHasService(RoutingHelpersExtension::class);
    }

    public function testOptionalPrefixTrue(): void
    {
        $this->loadBundleExtension([
            'optional_prefix' => true,
            'twig' => [
                'object_as_parameters' => false,
                'routing_helpers' => false,
            ],
        ]);
        $this->compile();

        $this->assertInstanceOf(RouterDecorator::class, $this->container->get('router'));
        $this->assertInstanceOf(LoaderDecorator::class, $this->container->get('routing.loader'));
        $this->assertSame(RoutingExtension::class, \get_class($this->container->get('twig.extension.routing')));
        $this->assertContainerBuilderNotHasService(RoutingHelpersExtension::class);
    }

    protected function setUpContainer(ContainerBuilder $container): void
    {
        $container->register(FileLocator::class);

        $container->register('request_stack', RequestStack::class);

        $container
            ->register('routing.loader', PhpFileLoader::class)
            ->setPublic(true)
            ->setArguments([
                '$locator' => new Reference(FileLocator::class),
            ]);

        $container
            ->register('router', Router::class)
            ->setPublic(true)
            ->setArguments([
                '$loader' => new Reference('routing.loader'),
                '$resource' => 'resource',
            ]);

        $container
            ->register('twig.extension.routing', RoutingExtension::class)
            ->setPublic(true)
            ->setArguments([
                '$generator' => new Reference('router'),
            ]);

        $container->register('property_accessor', PropertyAccessor::class)
            ->setPublic(true);

        $this->exposeService(RoutingHelpersExtension::class);
    }

    protected function getBundle(): BundleInterface
    {
        return new RuworkRoutingToolsBundle();
    }
}
