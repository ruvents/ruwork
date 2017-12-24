<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Ruwork\FrujaxBundle\EventListener\FrujaxListener;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRedirectResponse;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class FrujaxListenerTest extends TestCase
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new FrujaxListener());
    }

    public function testNonFrujaxRequest(): void
    {
        $request = Request::create('/');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = new Response();

        $event = $this->createFilterResponseEvent($request, $response);
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $actualResponse = $event->getResponse();

        $this->assertSame($response, $actualResponse);
        $this->assertFalse($actualResponse->headers->has('Frujax-Request-Url'));
    }

    public function testFrujaxRequest(): void
    {
        $request = Request::create('/');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('Frujax', 1);

        $response = new Response();

        $event = $this->createFilterResponseEvent($request, $response);
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $actualResponse = $event->getResponse();

        $this->assertSame($response, $actualResponse);
        $this->assertSame('/', $response->headers->get('Frujax-Request-Url'));
        $this->assertSame('must-revalidate, no-cache, no-store, private', $response->headers->get('Cache-Control'));
    }

    public function testRedirect(): void
    {
        $request = Request::create('/');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('Frujax', 1);
        $request->headers->set('Frujax-Intercept-Redirect', 1);

        $response = new RedirectResponse($target = '/test');
        $response->setCharset('utf8');

        $event = $this->createFilterResponseEvent($request, $response);
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);

        /** @var FrujaxRedirectResponse $actualResponse */
        $actualResponse = $event->getResponse();

        $this->assertNotSame($response, $actualResponse);
        $this->assertInstanceOf(FrujaxRedirectResponse::class, $actualResponse);
        $this->assertSame('must-revalidate, no-cache, no-store, private', $actualResponse->headers->get('Cache-Control'));
        $this->assertSame('utf8', $actualResponse->getCharset());
        $this->assertSame($target, $actualResponse->getTargetUrl());
        $this->assertSame($target, $actualResponse->headers->get('Frujax-Redirect-Url'));
        $this->assertFalse($actualResponse->headers->has('Location'));
    }

    private function createFilterResponseEvent(Request $request, Response $response): FilterResponseEvent
    {
        /** @var HttpKernelInterface $kernel */
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        return new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);
    }
}
