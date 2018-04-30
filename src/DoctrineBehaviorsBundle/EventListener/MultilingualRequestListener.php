<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\EventListener;

use Ruwork\DoctrineBehaviorsBundle\Multilingual\CurrentLocaleAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class MultilingualRequestListener implements EventSubscriberInterface
{
    /**
     * @var CurrentLocaleAwareInterface[]
     */
    private $objects;
    private $currentLocale;

    public function __construct()
    {
        $this->objects = new \SplObjectStorage();
        $this->currentLocale = \Locale::getDefault();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }

    public function register(CurrentLocaleAwareInterface $object): void
    {
        $object->setCurrentLocale($this->currentLocale);
        $this->objects->attach($object);
    }

    public function onRequest(GetResponseEvent $event): void
    {
        $this->currentLocale = $event->getRequest()->getLocale();

        foreach ($this->objects as $object) {
            $object->setCurrentLocale($this->currentLocale);
        }
    }
}
