<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Configurator;

use Ruwork\ObjectStore\Normalizer\NormalizerInterface;
use Ruwork\ObjectStore\Storage\StorageInterface;

interface StoreConfiguratorInterface
{
    public function getClass(): ?string;

    public function setClass(?string $class): self;

    public function getDefaultFactory(): ?callable;

    public function setDefaultFactory(?callable $defaultFactory): self;

    public function getNormalizer(): ?NormalizerInterface;

    public function setNormalizer(?NormalizerInterface $normalizer): self;

    public function getStorage(): ?StorageInterface;

    public function setStorage(?StorageInterface $storage): self;
}
