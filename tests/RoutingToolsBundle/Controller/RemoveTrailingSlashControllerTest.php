<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Controller;

use PHPUnit\Framework\TestCase;
use Ruwork\RoutingToolsBundle\RedirectFactory\RedirectFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RemoveTrailingSlashControllerTest extends TestCase
{
    public function testInvoke()
    {
        $factory = $this->createMock(RedirectFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->with('http://local.dev/url', 301)
            ->willReturn($this->createMock(RedirectResponse::class));

        $controller = new RemoveTrailingSlashController($factory);

        $request = Request::create('http://local.dev/url/'.urlencode(' '));

        $controller($request);
    }
}
