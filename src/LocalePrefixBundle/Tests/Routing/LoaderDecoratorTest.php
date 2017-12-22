<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Ruwork\LocalePrefixBundle\Routing\LoaderDecorator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;

class LoaderDecoratorTest extends TestCase
{
    public function testDecorated(): void
    {
        $mockLoader = $this->getMockBuilder(LoaderInterface::class)->getMock();

        $mockLoader->expects($this->once())
            ->method('load')
            ->with($this->equalTo($resource = 'res'), $this->equalTo($type = 'type'))
            ->willReturn($loadResult = 'loaded');

        $mockLoader->expects($this->once())
            ->method('supports')
            ->with($this->equalTo($resource), $this->equalTo($type))
            ->willReturn(true);

        $mockLoader->expects($this->once())
            ->method('getResolver')
            ->willReturn($resolver = new LoaderResolver());

        $mockLoader->expects($this->once())
            ->method('setResolver')
            ->with($this->equalTo($resolver));

        /** @var LoaderInterface $mockLoader */
        $loader = new LoaderDecorator($mockLoader, $ls = ['ru', 'en'], $dl = 'ru');

        // test simply decorated methods
        $this->assertEquals($loadResult, $loader->load($resource, $type));
        $this->assertTrue($loader->supports($resource, $type));
        $this->assertEquals($resolver, $loader->getResolver());
        $loader->setResolver($resolver);
    }
}
