<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\EventListener;

use Ruwork\DoctrineBehaviorsBundle\Multilingual\MultilingualInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class MultilingualRequestListener implements EventSubscriberInterface
{
    /**
     * @var MultilingualInterface[]
     */
    private $multilinguals;
    private $currentLocale;

    public function __construct(string $defaultLocale)
    {
        $this->multilinguals = new \SplObjectStorage();
        $this->currentLocale = $defaultLocale;
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

    public function register(MultilingualInterface $object): void
    {
        $object->setCurrentLocale($this->currentLocale);
        $this->multilinguals->attach($object);
    }

    public function onRequest(GetResponseEvent $event): void
    {
        $this->currentLocale = $event->getRequest()->getLocale();

        foreach ($this->multilinguals as $object) {
            $object->setCurrentLocale($this->currentLocale);
        }
    }
}
