<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Saver;

use Symfony\Component\Form\FormInterface;

interface SaverCollectorInterface
{
    public function add(FormInterface $form, callable $saver): void;
}
