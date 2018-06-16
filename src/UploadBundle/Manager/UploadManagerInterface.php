<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Manager;

interface UploadManagerInterface
{
    /**
     * @param object|string $object
     */
    public function isUpload($object): bool;

    /**
     * @param object $object
     */
    public function isRegistered($object): bool;

    /**
     * @param object $object
     */
    public function register($object, $source): void;

    /**
     * @param object $object
     */
    public function getSource($object);

    /**
     * @param object $object
     */
    public function detach($object): void;

    public function clear(): void;

    /**
     * @param object $object
     */
    public function save($object): void;

    public function saveAll(): void;

    /**
     * @param object $object
     */
    public function delete($object): void;
}
