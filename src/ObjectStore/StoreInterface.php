<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore;

interface StoreInterface
{
    public function getClass(): string;

    /**
     * @return object
     */
    public function get();

    /**
     * @param object $object
     */
    public function set($object): void;

    public function save(): void;

    public function clear(): void;
}
