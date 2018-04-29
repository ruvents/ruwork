<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\FrujaxUtils;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class FrujaxRedirectListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', 10],
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!FrujaxUtils::isFrujaxRequest($request)) {
            return;
        }

        $response = $event->getResponse();

        if (!$response instanceof RedirectResponse) {
            return;
        }

        if (!$request->headers->has(FrujaxHeaders::FRUJAX_INTERCEPT_REDIRECT)) {
            return;
        }

        $event->setResponse(FrujaxRedirectResponse::createFromRedirectResponse($response));
    }
}
