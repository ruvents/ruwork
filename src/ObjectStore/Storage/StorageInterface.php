<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Storage;

interface StorageInterface
{
    /**
     * @return mixed Null if the storage is empty
     */
    public function get();

    public function set($data): void;

    public function clear(): void;
}
