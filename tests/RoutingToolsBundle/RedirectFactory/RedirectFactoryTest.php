<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\RedirectFactory;

use PHPUnit\Framework\TestCase;
use Ruwork\RoutingToolsBundle\RedirectFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectFactoryTest extends TestCase
{
    public function testCreateDefault()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $factory = new RedirectFactory($urlGenerator, $requestStack);

        $redirect = $factory->url('/url');

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(302, $redirect->getStatusCode());
    }

    public function testCreate()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $factory = new RedirectFactory($urlGenerator, $requestStack);

        $redirect = $factory->url('/url', 301, ['header' => 'value']);

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(301, $redirect->getStatusCode());
        $this->assertSame('value', $redirect->headers->get('header'));
    }

    public function testCreateForRouteDefault()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->once())
            ->method('generate')
            ->with('route', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/url');
        $requestStack = $this->createMock(RequestStack::class);

        $factory = new RedirectFactory($urlGenerator, $requestStack);

        $redirect = $factory->route('route');

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
        $requestStack = $this->createMock(RequestStack::class);

        $factory = new RedirectFactory($generator, $requestStack);

        $redirect = $factory->route('route', ['a' => 1], 301, ['header' => 'value']);

        $this->assertSame('/url', $redirect->getTargetUrl());
        $this->assertSame(301, $redirect->getStatusCode());
        $this->assertSame('value', $redirect->headers->get('header'));
    }
}
