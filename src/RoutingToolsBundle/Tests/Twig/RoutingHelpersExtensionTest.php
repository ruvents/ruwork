<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Ruwork\RoutingToolsBundle\Twig\RoutingHelpersExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RoutingHelpersExtensionTest extends TestCase
{
    public function testWithEmptyRequest(): void
    {
        $extension = new RoutingHelpersExtension(new RequestStack());

        $this->assertNull($extension->getCurrentRoute());
        $this->assertNull($extension->getCurrentRouteParams());
    }

    public function testWithRequest(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], ['_route' => 'test', '_route_params' => ['id' => 1]]));
        $extension = new RoutingHelpersExtension($requestStack);

        $this->assertSame('test', $extension->getCurrentRoute());
        $this->assertSame(['id' => 1], $extension->getCurrentRouteParams());
    }
}
