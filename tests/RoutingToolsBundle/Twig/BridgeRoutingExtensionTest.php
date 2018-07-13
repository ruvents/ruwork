<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Twig;

use PHPUnit\Framework\TestCase;
use Ruwork\RoutingToolsBundle\Fixtures\News;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * @internal
 */
class BridgeRoutingExtensionTest extends TestCase
{
    /**
     * @var BridgeRoutingExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $collection = new RouteCollection();
        $collection->add('news', new Route('/{slug}/{category}/{id}', ['slug' => 'news'], ['category' => '\w+', 'id' => '\d+']));

        $loader = $this->getMockBuilder(LoaderInterface::class)->getMock();
        $loader->expects($this->any())->method('load')->willReturn($collection);
        /** @var LoaderInterface $loader */
        $router = new Router($loader, 'resource');

        $this->extension = new BridgeRoutingExtension($router, $router, PropertyAccess::createPropertyAccessor());
    }

    public function testPath(): void
    {
        $news = new News(1, 'urgent');
        $this->assertSame('/news/urgent/1', $this->extension->getPath('news', $news));
        $this->assertSame('/news/urgent/1', $this->extension->getPath('news', ['id' => 1, 'category' => 'urgent']));
    }

    public function testUrl(): void
    {
        $news = new News(1, 'urgent');
        $this->assertSame('http://localhost/news/urgent/1', $this->extension->getUrl('news', $news));
        $this->assertSame('http://localhost/news/urgent/1', $this->extension->getUrl('news', ['id' => 1, 'category' => 'urgent']));
    }
}
