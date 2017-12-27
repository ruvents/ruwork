<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\Storage;

interface StorageInterface
{
    public function has(string $key): bool;

    public function get(string $key);

    public function set(string $key, $value): void;

    public function remove(string $key): void;
}
