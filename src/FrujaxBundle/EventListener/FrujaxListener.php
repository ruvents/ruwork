<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class FrujaxListener
{
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

        $response->headers->add(['Frujax-Request-Url' => $request->getRequestUri()]);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
    }
}
