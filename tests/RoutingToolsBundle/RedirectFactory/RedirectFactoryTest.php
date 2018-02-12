<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\RedirectFactory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectFactoryTest extends TestCase
{
    public function testCreateDefault()
    {
        $factory = new RedirectFactory($this->createMock(UrlGeneratorInterface::class));

        $redirect = $factory->create('/url');

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(302, $redirect->getStatusCode());
    }

    public function testCreate()
    {
        $factory = new RedirectFactory($this->createMock(UrlGeneratorInterface::class));

        $redirect = $factory->create('/url', 301, ['header' => 'value']);

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(301, $redirect->getStatusCode());
        $this->assertSame('value', $redirect->headers->get('header'));
    }

    public function testCreateForRouteDefault()
    {
        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator->expects($this->once())
            ->method('generate')
            ->with('route', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/url');

        $factory = new RedirectFactory($generator);

        $redirect = $factory->createForRoute('route');

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(302, $redirect->getStatusCode());
    }

    public function testCreateForRoute()
    {
        $generator = $this->createMock(UrlGeneratorInterface::class);
        $generator->expects($this->once())
            ->method('generate')
            ->with('route', ['a' => 1], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/url');

        $factory = new RedirectFactory($generator);

        $redirect = $factory->createForRoute('route', ['a' => 1], 301, ['header' => 'value']);

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(301, $redirect->getStatusCode());
        $this->assertSame('value', $redirect->headers->get('header'));
    }
}
