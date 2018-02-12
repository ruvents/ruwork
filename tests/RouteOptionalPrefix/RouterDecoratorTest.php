<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class RouterDecoratorTest extends TestCase
{
    public function testSetContext(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $requestContext = $this->createMock(RequestContext::class);

        $router->expects($this->once())
            ->method('setContext')
            ->with($requestContext);

        $decorator = new RouterDecorator($router);

        $decorator->setContext($requestContext);
    }

    public function testGetContext(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $requestContext = $this->createMock(RequestContext::class);

        $router->expects($this->once())
            ->method('getContext')
            ->willReturn($requestContext);

        $decorator = new RouterDecorator($router);

        $this->assertSame($requestContext, $decorator->getContext());
    }

    public function testGetRouteCollection(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $collection = $this->createMock(RouteCollection::class);

        $router->expects($this->once())
            ->method('getRouteCollection')
            ->willReturn($collection);

        $decorator = new RouterDecorator($router);

        $this->assertSame($collection, $decorator->getRouteCollection());
    }

    public function testMatch(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $pathInfo = '/path_info';
        $parameters = ['a' => 1];

        $router->expects($this->once())
            ->method('match')
            ->with($pathInfo)
            ->willReturn($parameters);

        $decorator = new RouterDecorator($router);

        $this->assertSame($parameters, $decorator->match($pathInfo));
    }

    public function testMatchRequest(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $pathInfo = '/path_info';
        $parameters = ['a' => 1];

        $router->expects($this->once())
            ->method('match')
            ->with($pathInfo)
            ->willReturn($parameters);

        $decorator = new RouterDecorator($router);

        $this->assertSame($parameters, $decorator->matchRequest(Request::create($pathInfo)));
    }

    public function testMatchRequestForRequestMatcherInterface(): void
    {
        $router = $this->createMock([RouterInterface::class, RequestMatcherInterface::class]);
        $request = Request::create('/pathinfo');
        $parameters = ['a' => 1];

        $router->expects($this->once())
            ->method('matchRequest')
            ->with($request)
            ->willReturn($parameters);

        $decorator = new RouterDecorator($router);

        $this->assertSame($parameters, $decorator->matchRequest($request));
    }

    public function testGenerate(): void
    {
        $name = 'route';
        $parameters = ['a' => 1];
        $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH;
        $url = '/url';

        $collection = $this->createMock(RouteCollection::class);
        $collection->method('get')
            ->willReturn(null);

        $router = $this->createMock(RouterInterface::class);

        $router->method('getRouteCollection')
            ->willReturn($collection);

        $router->expects($this->once())
            ->method('generate')
            ->with($name, $parameters, $referenceType)
            ->willReturn($url);

        $decorator = new RouterDecorator($router);

        $this->assertSame($url, $decorator->generate($name, $parameters, $referenceType));
    }

    /**
     * @dataProvider getMatchData
     */
    public function testMatchUrl(iterable $routes, string $url, array $expectedParameters): void
    {
        $loader = $this->createLoader($routes);
        $router = new RouterDecorator(new Router($loader, 'res'));

        $this->assertEquals($expectedParameters, $router->match($url));
    }

    public function getMatchData(): \Generator
    {
        yield [
            ['unprefixed' => new Route('/unprefixed')],
            '/unprefixed',
            ['_route' => 'unprefixed'],
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            '/',
            ['var' => 'ru', '_route' => 'index'],
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            '/en/',
            ['var' => 'en', '_route' => 'index'],
        ];
    }

    /**
     * @dataProvider getGenerateData
     */
    public function testGenerateUrl(
        iterable $routes,
        array $contextParameters,
        string $name,
        array $parameters,
        string $expectedUrl
    ): void {
        $loader = $this->createLoader($routes);
        $router = new RouterDecorator(new Router($loader, 'res'));
        $router->getContext()->setParameters($contextParameters);

        $this->assertEquals($expectedUrl, $router->generate($name, $parameters));
    }

    public function getGenerateData()
    {
        yield [
            ['unprefixed' => new Route('/unprefixed')],
            [],
            'unprefixed',
            ['var' => 'en'],
            '/unprefixed?var=en',
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            [],
            'index',
            [],
            '/',
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            [],
            'index',
            ['var' => 'ru'],
            '/',
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            ['var' => 'ru'],
            'index',
            [],
            '/',
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            ['var' => 'en'],
            'index',
            [],
            '/en/',
        ];

        yield [
            [
                'index' => new Route(
                    '/{var}',
                    ['var' => 'ru'],
                    ['var' => '(en)/|'],
                    ['prefix_variable' => 'var']
                ),
            ],
            ['var' => 'ru'],
            'index',
            ['var' => 'en'],
            '/en/',
        ];
    }

    private function createLoader(iterable $routes): LoaderInterface
    {
        $collection = new RouteCollection();

        foreach ($routes as $name => $route) {
            $collection->add($name, $route);
        }

        $loader = $this->createMock(LoaderInterface::class);
        $loader->method('load')->willReturn($collection);

        return $loader;
    }
}
