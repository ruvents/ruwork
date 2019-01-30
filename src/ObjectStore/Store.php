<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore;

use Ruwork\ObjectStore\Exception\InvalidArgumentException;
use Ruwork\ObjectStore\Exception\UnexpectedValueException;
use Ruwork\ObjectStore\Normalizer\NormalizerInterface;
use Ruwork\ObjectStore\Normalizer\NullNormalizer;
use Ruwork\ObjectStore\Storage\StorageInterface;

final class Store implements StoreInterface
{
    private $class;
    private $storage;
    private $defaultFactory;
    private $normalizer;
    private $data;
    private $loaded = false;

    public function __construct(
        string $class,
        StorageInterface $storage,
        ?callable $defaultFactory = null,
        ?NormalizerInterface $normalizer = null
    ) {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $this->class = $class;
        $this->storage = $storage;
        $this->defaultFactory = $defaultFactory
            ?? static function () {
                return null;
            };
        $this->normalizer = $normalizer ?? new NullNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        if ($this->loaded) {
            return $this->data;
        }

        $data = $this->storage->get();

        if (null === $data) {
            $data = ($this->defaultFactory)();
        } else {
            $data = $this->normalizer->denormalize($data, $this->class);
        }

        if (null !== $data && !$data instanceof $this->class) {
            throw UnexpectedValueException::createForValue($data, 'null or '.$this->class);
        }

        $this->loaded = true;

        return $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function set($object): void
    {
        $this->data = $object;
        $this->loaded = true;
    }

    /**
     * {@inheritdoc}
     */
    public function save(): void
    {
        if (!$this->loaded) {
            return;
        }

        $normalized = $this->normalizer->normalize($this->data);
        $this->storage->set($normalized);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->storage->clear();
    }
}
