<?php

declare(strict_types=1);

namespace Ruwork\Reform\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class DynamicFormListener implements EventSubscriberInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        ($this->callable)($event->getData(), $event->getForm());
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        ($this->callable)($form->getData(), $form);
    }
}
