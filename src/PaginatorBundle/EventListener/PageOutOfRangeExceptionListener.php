<?php

namespace Ruwork\PaginatorBundle\EventListener;

use Ruwork\Paginator\Exception\PageOutOfRangeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class PageOutOfRangeExceptionListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(GetResponseForExceptionEvent $event)
    {
        if (($exception = $event->getException()) instanceof PageOutOfRangeException) {
            $event->setException(new NotFoundHttpException($exception->getMessage(), $exception));
        }
    }
}
