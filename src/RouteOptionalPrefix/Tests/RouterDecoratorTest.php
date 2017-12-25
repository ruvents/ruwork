<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix\Tests;

use PHPUnit\Framework\TestCase;
use Ruwork\RouteOptionalPrefix\LoaderDecorator;
use Ruwork\RouteOptionalPrefix\RouterDecorator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class RouterDecoratorTest extends TestCase
{
    /**
     * @var RouterDecorator
     */
    private $router;

    protected function setUp(): void
    {
        $collection = new RouteCollection();

        $collection->add('prefixed', new Route('/', [], [], [
            'prefix_variable' => '_locale',
            'prefix_default' => 'ru',
            'prefix_requirements' => 'en',
        ]));

        $collection->add('unprefixed', new Route('/unprefixed'));

        $loader = $this->getMockBuilder(LoaderInterface::class)->getMock();
        $loader->expects($this->any())->method('load')->willReturn($collection);
        /** @var LoaderInterface $loader */
        $loader = new LoaderDecorator($loader);

        $this->router = new RouterDecorator(new Router($loader, 'resource'));
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate(string $route, array $parameters, string $expectedUrl): void
    {
        $this->assertSame($expectedUrl, $this->router->generate($route, $parameters));
    }

    public function generateData(): array
    {
        return [
            ['prefixed', ['_locale' => 'en'], '/en/'],
            ['prefixed', [], '/'],
            ['prefixed', ['_locale' => 'ru'], '/'],
            ['unprefixed', ['_locale' => 'ru'], '/unprefixed?_locale=ru'],
        ];
    }

    public function testGenerateRequirements(): void
    {
        $this->expectException(InvalidParameterException::class);

        $this->router->generate('prefixed', ['_locale' => 'abc']);
    }

    public function testGenerateNoRoute(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->router->generate('non_existing_route');
    }

    /**
     * @dataProvider matchData
     */
    public function testMatch(string $url, array $expectedParameters): void
    {
        $this->assertSame($expectedParameters, $this->router->match($url));
        $this->assertSame($expectedParameters, $this->router->matchRequest(Request::create($url)));
    }

    public function matchData(): array
    {
        return [
            ['/unprefixed', ['_route' => 'unprefixed']],
            ['/', ['_locale' => 'ru', '_route' => 'prefixed']],
            ['/en/', ['_locale' => 'en', '_route' => 'prefixed']],
        ];
    }

    public function testMatchNoRoute(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->router->match('/non-matching-path');
    }

    public function testDecoration(): void
    {
        $mock = $this->getMockBuilder(RouterInterface::class)->getMock();

        $mock->expects($this->once())
            ->method('getContext')
            ->willReturn($context = new RequestContext());

        $mock->expects($this->once())
            ->method('setContext')
            ->with($this->equalTo($context));

        /** @var RouterInterface $mock */
        $router = new RouterDecorator($mock);

        $this->assertEquals($context, $router->getContext());
        $router->setContext($context);
    }
}
