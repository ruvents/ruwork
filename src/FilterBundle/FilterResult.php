<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Symfony\Component\Form\FormInterface;

final class FilterResult implements FilterResultInterface
{
    private $object;
    private $form;
    private $options;

    public function __construct($object, FormInterface $form, array $options)
    {
        $this->object = $object;
        $this->form = $form;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
