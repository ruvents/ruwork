<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Manager;

use Ruwork\UploadBundle\Source\ResolvedSource;

interface UploadManagerInterface
{
    /**
     * @param object $object
     */
    public function register($object, $source): void;

    /**
     * @param object $object
     */
    public function getResolvedSource($object): ResolvedSource;

    /**
     * @param object $object
     */
    public function save($object): void;

    /**
     * @param object $object
     */
    public function detach($object): void;

    public function clear(): void;

    /**
     * @param object $object
     */
    public function getPath($object): string;

    /**
     * @param object $object
     */
    public function locate($object): string;

    /**
     * @param object $object
     */
    public function delete($object): void;
}
