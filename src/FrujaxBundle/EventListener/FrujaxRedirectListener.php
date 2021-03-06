<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxUtils;
use Ruwork\FrujaxBundle\HttpFoundation\Response\FrujaxRedirectResponse;
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

        if (!$request->headers->get(FrujaxHeaders::FRUJAX_INTERCEPT_REDIRECT, false)) {
            return;
        }

        $event->setResponse(FrujaxRedirectResponse::createFromRedirect($response));
    }
}
