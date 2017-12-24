<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Ruwork\LocalePrefixBundle\Routing\LoaderDecorator;
use Ruwork\LocalePrefixBundle\Routing\Router;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class FrameworkRouterDecoratorTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testGenerate($path, array $parameters, $url, $currentLocale, $i18n = true): void
    {
        $locales = ['ru', 'en'];
        $defaultLocale = 'ru';

        $collection = (new RouteCollection());
        $collection->add($name = 'test', new Route($path, [], [], ['locale_prefixed' => $i18n]));

        $i18nRouter = $this->createRouter($collection, $locales, $defaultLocale, $currentLocale);

        // test generate
        $this->assertEquals($url, $i18nRouter->generate($name, $parameters));

        // test match
        $expectedParams = array_merge($parameters, [
            '_route' => $name,
            '_locale' => $parameters['_locale'] ?? $currentLocale,
        ]);
        $pathInfo = parse_url($url, PHP_URL_PATH);
        $request = $this->createRequest($pathInfo);

        $this->assertEquals($expectedParams, $i18nRouter->match($pathInfo));
        $this->assertEquals($expectedParams, $i18nRouter->matchRequest($request));
    }

    public function testGenerateNonExistentRoute(): void
    {
        $this->expectException(\Symfony\Component\Routing\Exception\RouteNotFoundException::class);

        $i18nRouter = $this->createRouter(new RouteCollection(), ['ru'], 'ru', 'ru');
        $i18nRouter->generate('a');
    }

    public function data()
    {
        return [
            ['/', [], '/', 'ru'],
            ['/', [], '/en/', 'en'],
            ['/', ['_locale' => 'en'], '/en/', 'ru'],
            ['/', ['_locale' => 'en'], '/en/', 'en'],
            // ['/', ['_locale' => 'en'], '/?_locale=en', 'ru', false],
        ];
    }

    private function createRouter(RouteCollection $collection, array $locales, $defaultLocale, $currentLocale)
    {
        $i18nRouter = new Router($this->createContainer($collection, $locales, $defaultLocale), 'res');
        $i18nRouter->setRequestStack($this->createRequestStack($currentLocale));
        $i18nRouter->setDefaultLocale($defaultLocale);

        return $i18nRouter;
    }

    private function createContainer(RouteCollection $collection, array $locales, $defaultLocale)
    {
        $container = $this->getMockBuilder(Container::class)
            ->setMethods(['get'])
            ->getMock();

        $i18nLoader = new LoaderDecorator($this->createRouteCollectionLoader($collection), $locales, $defaultLocale);

        $container->expects($this->any())
            ->method('get')
            ->willReturn($i18nLoader);

        /* @var Container $container */

        return $container;
    }

    private function createRouteCollectionLoader(RouteCollection $collection)
    {
        $loader = $this->getMockBuilder(LoaderInterface::class)->getMock();
        $loader->expects($this->any())->method('load')->willReturn($collection);

        /* @var LoaderInterface $loader */

        return $loader;
    }

    private function createRequestStack($locale)
    {
        $r = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLocale'])
            ->getMock();

        $r->expects($this->any())
            ->method('getLocale')
            ->willReturn($locale);

        $rs = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentRequest'])
            ->getMock();

        $rs->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturn($r);

        /* @var RequestStack $rs */

        return $rs;
    }

    private function createRequest($pathInfo)
    {
        $r = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathInfo'])
            ->getMock();

        $r->expects($this->any())
            ->method('getPathInfo')
            ->willReturn($pathInfo);

        /* @var Request $r */

        return $r;
    }
}
