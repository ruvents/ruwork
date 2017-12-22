<?php

namespace Ruwork\ApiBundle\EventListener;

use Ruwork\ApiBundle\Helper;
use Ruwork\ApiBundle\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['setLocale', 16],
            KernelEvents::EXCEPTION => 'onException',
            KernelEvents::VIEW => ['onView', 1],
        ];
    }

    public function setLocale(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (Helper::isApiRequest($request) && null !== $locale = $request->get('locale')) {
            $request->setLocale($locale);
        }
    }

    public function onException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        if ($event->isMasterRequest() && Helper::isApiRequest($request) && $exception instanceof HttpExceptionInterface) {
            $event->setResponse(new ApiResponse(null, $exception->getStatusCode()));
        }
    }

    public function onView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        if ($event->isMasterRequest() && Helper::isApiRequest($request)) {
            $event->setResponse(new ApiResponse($event->getControllerResult()));
        }
    }
}
