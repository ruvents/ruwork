<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class FrujaxTemplateListener implements EventSubscriberInterface
{
    private $name;
    private $code;

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

        $this->name = $request->headers->get('Frujax-Name');
    }

    public function onResponse(FilterResponseEvent $event): void
    {
        if (null !== $this->code) {
            $event->setResponse(new Response($this->code));
        }
    }

    public function onFinishRequest(): void
    {
        $this->name = null;
        $this->code = null;
    }

    public function register(string $name, string $code): void
    {
        if ($this->name === $name) {
            $this->code = $code;
        }
    }
}
