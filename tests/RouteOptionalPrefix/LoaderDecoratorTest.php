<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @internal
 */
class LoaderDecoratorTest extends TestCase
{
    public function testSupports()
    {
        $loader = $this->createMock(LoaderInterface::class);

        $resource = 'resource';
        $type = 'type';

        $loader->expects($this->once())
            ->method('supports')
            ->with($resource, $type)
            ->willReturn(true);

        $decorator = new LoaderDecorator($loader);

        $this->assertTrue($decorator->supports($resource, $type));
    }

    public function testGetResolver()
    {
        $loader = $this->createMock(LoaderInterface::class);

        $resolver = $this->createMock(LoaderResolverInterface::class);

        $loader->expects($this->once())
            ->method('getResolver')
            ->willReturn($resolver);

        $decorator = new LoaderDecorator($loader);

        $this->assertSame($resolver, $decorator->getResolver());
    }

    public function testSetResolver()
    {
        $loader = $this->createMock(LoaderInterface::class);

        $resolver = $this->createMock(LoaderResolverInterface::class);

        $loader->expects($this->once())
            ->method('setResolver')
            ->with($resolver);

        $decorator = new LoaderDecorator($loader);

        $decorator->setResolver($resolver);
    }

    public function testLoadIgnoresNotRoutingCollection(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Decorated route loader is expected to return an instance of Symfony\Component\Routing\RouteCollection.');

        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())
            ->method('load')
            ->willReturn(null);

        (new LoaderDecorator($loader))->load('res');
    }

    public function testLoadIgnoresRouteWithoutOption(): void
    {
        $loader = $this->createLoaderDecorator($expectedRoutes = [
            'test' => new Route('/test'),
            'test2' => new Route('/test2/{a}', ['a' => 1]),
        ]);

        $actualRoutes = \iterator_to_array($loader->load('resource'));

        $this->assertSame($expectedRoutes, $actualRoutes);
    }

    public function testLoadFailsWithoutDefault(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Route "route" with optional prefix "/{_locale}" must have a default value for "_locale".');

        $this
            ->createLoaderDecorator([
                'route' => new Route('/', [], [], ['prefix_variable' => '_locale']),
            ])
            ->load('res');
    }

    public function testLoadRouteWithPrefix(): void
    {
        $collection = $this
            ->createLoaderDecorator([
                'route' => new Route(
                    '/',
                    ['_locale' => 'en'],
                    [],
                    ['prefix_variable' => '_locale']
                ),
            ])
            ->load('res');

        $actualRoute = $collection->get('route');
        $expectedRoute = new Route(
            '/{_locale}',
            ['_locale' => 'en'],
            ['_locale' => '([^/]+)/|'],
            ['prefix_variable' => '_locale']
        );

        $this->assertEquals($expectedRoute, $actualRoute);
    }

    public function testLoadRouteWithRequirement(): void
    {
        $collection = $this
            ->createLoaderDecorator([
                'route' => new Route(
                    '/',
                    ['_locale' => 'en'],
                    ['_locale' => 'ru'],
                    ['prefix_variable' => '_locale']
                ),
            ])
            ->load('res');

        $actualRoute = $collection->get('route');
        $expectedRoute = new Route(
            '/{_locale}',
            ['_locale' => 'en'],
            ['_locale' => '(ru)/|'],
            ['prefix_variable' => '_locale']
        );

        $this->assertEquals($expectedRoute, $actualRoute);
    }

    private function createLoaderDecorator(iterable $routes): LoaderDecorator
    {
        $loader = $this->createMock(LoaderInterface::class);

        $collection = new RouteCollection();

        foreach ($routes as $name => $route) {
            $collection->add($name, $route);
        }

        $loader->expects($this->once())
            ->method('load')
            ->willReturn($collection);

        return new LoaderDecorator($loader);
    }
}
