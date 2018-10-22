<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Configurator;

use Ruwork\ObjectStore\Exception\InvalidArgumentException;
use Ruwork\ObjectStore\Normalizer\NormalizerInterface;
use Ruwork\ObjectStore\Storage\StorageInterface;
use Ruwork\ObjectStore\Store;

final class StoreBuilder implements StoreConfiguratorInterface
{
    private $class;
    private $defaultFactory;
    private $normalizer;
    private $storage;

    /**
     * {@inheritdoc}
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function setClass(?string $class): StoreConfiguratorInterface
    {
        $this->class = $class;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultFactory(): ?callable
    {
        return $this->defaultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultFactory(?callable $defaultFactory): StoreConfiguratorInterface
    {
        $this->defaultFactory = $defaultFactory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNormalizer(): ?NormalizerInterface
    {
        return $this->normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function setNormalizer(?NormalizerInterface $normalizer): StoreConfiguratorInterface
    {
        $this->normalizer = $normalizer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorage(): ?StorageInterface
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function setStorage(?StorageInterface $storage): StoreConfiguratorInterface
    {
        $this->storage = $storage;

        return $this;
    }

    public function build(): Store
    {
        if (null === $this->class) {
            throw new InvalidArgumentException('Class is not set.');
        }

        if (null === $this->storage) {
            throw new InvalidArgumentException('Storage is not set.');
        }

        return new Store(
            $this->class,
            $this->storage,
            $this->defaultFactory,
            $this->normalizer
        );
    }
}
