<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRequestChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class FrujaxResponseListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!FrujaxRequestChecker::isFrujaxRequest($request)) {
            return;
        }

        $response = $event->getResponse();

        $response->headers->set(FrujaxHeaders::FRUJAX_URL, $request->getRequestUri());
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
    }
}
