<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

interface ResolvedSourceInterface
{
    public function getAttributes(): array;

    public function getTmpPath(): string;

    public function getPath(): string;

    public function isSaved(): bool;

    public function save(): void;
}
