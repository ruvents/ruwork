<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source\Handler;

interface SourceHandlerInterface
{
    public function supports($source): bool;

    public function getAttributes($source): array;

    public function write($source, array $attributes, string $target): void;
}
