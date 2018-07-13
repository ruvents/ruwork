<?php

declare(strict_types=1);

namespace Ruwork\PaginatorBundle\EventListener;

use PHPUnit\Framework\TestCase;
use Ruwork\Paginator\Exception\PageOutOfRangeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 */
class PageOutOfRangeExceptionListenerTest extends TestCase
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new PageOutOfRangeExceptionListener());
    }

    public function testNonTargetException(): void
    {
        /** @var HttpKernelInterface $kernel */
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $initialException = new \RuntimeException();

        $event = new GetResponseForExceptionEvent($kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $initialException);
        $this->dispatcher->dispatch(KernelEvents::EXCEPTION, $event);

        $this->assertSame($initialException, $event->getException());
    }

    public function testTargetException(): void
    {
        /** @var HttpKernelInterface $kernel */
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $initialException = new PageOutOfRangeException(10, 1);

        $event = new GetResponseForExceptionEvent($kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $initialException);
        $this->dispatcher->dispatch(KernelEvents::EXCEPTION, $event);

        $resultException = $event->getException();
        $this->assertInstanceOf(NotFoundHttpException::class, $resultException);
        $this->assertSame($initialException->getMessage(), $resultException->getMessage());
        $this->assertSame($initialException, $resultException->getPrevious());
    }
}
