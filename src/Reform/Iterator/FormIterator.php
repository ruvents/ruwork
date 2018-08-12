<?php

declare(strict_types=1);

namespace Ruwork\Reform\Iterator;

use Symfony\Component\Form\FormInterface;

final class FormIterator extends \IteratorIterator implements \RecursiveIterator
{
    public function __construct(FormInterface $form)
    {
        parent::__construct($form);
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return $this->current()->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return new static($this->current());
    }
}
