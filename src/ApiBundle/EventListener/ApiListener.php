<?php

declare(strict_types=1);

namespace Ruwork\ApiBundle\EventListener;

use Ruwork\ApiBundle\Helper;
use Ruwork\ApiBundle\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['setLocale', 16],
            KernelEvents::EXCEPTION => ['onException', -10],
            KernelEvents::VIEW => ['onView', 1],
        ];
    }

    public function setLocale(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (Helper::isApiRequest($request) && null !== $locale = $request->get('locale')) {
            $request->setLocale($locale);
        }
    }

    public function onException(GetResponseForExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        if ($event->isMasterRequest() && Helper::isApiRequest($request)) {
            $status = 500;

            if ($exception instanceof HttpExceptionInterface) {
                $status = $exception->getStatusCode();
            }

            $event->setResponse(new ApiResponse([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], $status));
        }
    }

    public function onView(GetResponseForControllerResultEvent $event): void
    {
        $request = $event->getRequest();

        if ($event->isMasterRequest() && Helper::isApiRequest($request)) {
            $event->setResponse(new ApiResponse($event->getControllerResult()));
        }
    }
}
