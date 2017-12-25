<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix\Tests;

use PHPUnit\Framework\TestCase;
use Ruwork\RouteOptionalPrefix\LoaderDecorator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;

class LoaderDecoratorTest extends TestCase
{
    public function testDecoration(): void
    {
        $mock = $this->getMockBuilder(LoaderInterface::class)->getMock();

        $mock->expects($this->once())
            ->method('load')
            ->with($this->equalTo($resource = 'resource'), $this->equalTo($type = 'type'))
            ->willReturn($loadResult = 'loaded');

        $mock->expects($this->once())
            ->method('supports')
            ->with($this->equalTo($resource), $this->equalTo($type))
            ->willReturn(true);

        $mock->expects($this->once())
            ->method('getResolver')
            ->willReturn($resolver = new LoaderResolver());

        $mock->expects($this->once())
            ->method('setResolver')
            ->with($this->equalTo($resolver));

        /** @var LoaderInterface $mock */
        $loader = new LoaderDecorator($mock);

        $this->assertEquals($loadResult, $loader->load($resource, $type));
        $this->assertTrue($loader->supports($resource, $type));
        $this->assertEquals($resolver, $loader->getResolver());
        $loader->setResolver($resolver);
    }
}
