<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\EventListener;

use Ruwork\UploadBundle\Form\Type\UploadType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

final class UploadListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => ['onPostSubmit', -1000],
        ];
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        if ($form->isRoot()) {
            $this->onPostSubmitRecursive($form);
        }
    }

    private function onPostSubmitRecursive(FormInterface $form): void
    {
        if (true === $form->getConfig()->getAttribute(UploadType::ATTRIBUTE)) {
            $form->getConfig()->getOption('submit_handler')($form->getData(), $form);
        }

        foreach ($form as $child) {
            $this->onPostSubmitRecursive($child);
        }
    }
}
