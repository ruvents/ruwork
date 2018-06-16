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
            $this->traverse($form);
        }
    }

    private function traverse(FormInterface $form): void
    {
        if (true === $form->getConfig()->getAttribute(UploadType::ATTRIBUTE) &&
            !$form->get(UploadType::FILE)->isEmpty()
        ) {
            if ($form->isValid()) {
                $form->getConfig()->getOption('on_valid_upload')($form->getData(), $form);
            } else {
                $form->getConfig()->getOption('on_invalid_upload')($form->getData(), $form);
            }
        }

        foreach ($form as $child) {
            self::traverse($child);
        }
    }
}
