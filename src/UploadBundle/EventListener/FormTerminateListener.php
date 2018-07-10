<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class FormTerminateListener implements EventSubscriberInterface
{
    /**
     * @var FormInterface[]
     */
    private $forms = [];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'onTerminate',
        ];
    }

    public function registerForm(FormInterface $form): void
    {
        $this->forms[] = $form;
    }

    public function onTerminate(): void
    {
        foreach ($this->forms as $form) {
            $upload = $form->getData();

            if (null === $upload || !$form->isValid() || $form->getRoot()->isValid()) {
                continue;
            }

            $form->getConfig()->getOption('on_terminate')($upload, $form);
        }

        $this->forms = [];
    }
}
