<?php

namespace Ruvents\RuworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractRuworkController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices()
    {
        return array_merge([
            EventDispatcherInterface::class,
        ], parent::getSubscribedServices());
    }

    protected function dispatch(string $eventName, Event $event = null): void
    {
        $this->container->get(EventDispatcherInterface::class)->dispatch($eventName, $event);
    }
}
