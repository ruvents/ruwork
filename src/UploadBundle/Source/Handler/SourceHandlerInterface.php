<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source\Handler;

interface SourceHandlerInterface
{
    public function supports($source): bool;

    public function write($source, string $target): void;
}
