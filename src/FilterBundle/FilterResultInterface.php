<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Symfony\Component\Form\FormInterface;

interface FilterResultInterface
{
    public function getObject();

    public function getForm(): FormInterface;

    public function getOptions(): array;
}
