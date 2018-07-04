<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class FrujaxPartListener implements EventSubscriberInterface
{
    private $expectedPart;
    private $content;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
            KernelEvents::RESPONSE => ['onResponse', -50],
            KernelEvents::FINISH_REQUEST => 'onFinishRequest',
        ];
    }

    public function onRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!FrujaxUtils::isFrujaxRequest($request)) {
            return;
        }

        $this->expectedPart = $request->headers->get(FrujaxHeaders::FRUJAX_PART);
    }

    public function onResponse(FilterResponseEvent $event): void
    {
        if (null !== $this->content) {
            $event->setResponse(new Response($this->content));
        }
    }

    public function onFinishRequest(): void
    {
        $this->expectedPart = null;
        $this->content = null;
    }

    public function onPart(string $name, string $content): void
    {
        if ($this->expectedPart === $name) {
            $this->content = $content;
        }
    }
}
