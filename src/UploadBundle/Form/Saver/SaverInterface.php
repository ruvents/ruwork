<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Saver;

interface SaverInterface
{
    public function save(): void;
}
