<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Saver;

use Symfony\Component\Form\FormInterface;

final class Saver implements SaverCollectorInterface, SaverInterface
{
    private $storage = [];

    /**
     * {@inheritdoc}
     */
    public function add(FormInterface $form, callable $saver): void
    {
        $this->storage[] = [$form, $saver];
    }

    /**
     * {@inheritdoc}
     */
    public function save(): void
    {
        /** @var FormInterface $form */
        foreach ($this->storage as $key => [$form, $saver]) {
            unset($this->storage[$key]);

            $upload = $form->getData();

            if (null === $upload || !$form->isValid() || $form->getRoot()->isValid()) {
                continue;
            }

            $saver($upload, $form);
        }
    }
}
