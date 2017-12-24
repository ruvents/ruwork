<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FrujaxListener implements EventSubscriberInterface
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

        if (!$event->isMasterRequest()
            || !$request->isXmlHttpRequest()
            || !$request->headers->has('Frujax')
        ) {
            return;
        }

        $response = $event->getResponse();

        if ($response instanceof RedirectResponse
            && $request->headers->has('Frujax-Intercept-Redirect')
        ) {
            $response = FrujaxRedirectResponse::createFromRedirectResponse($response);
            $event->setResponse($response);
        }

        $response->headers->set('Frujax-Request-Url', $request->getRequestUri());
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
    }
}
