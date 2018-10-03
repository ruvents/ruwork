<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Item;

final class LazyItem implements ClearableItemInterface
{
    private $id;
    private $initializer;
    private $clear;
    private $initialized = false;
    private $value;

    public function __construct(string $id, callable $initializer, ?callable $clear = null)
    {
        $this->id = $id;
        $this->initializer = $initializer;
        $this->clear = $clear;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        if (!$this->initialized) {
            $this->value = ($this->initializer)($this->id);
            $this->initialized = true;
        }

        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        if (null !== $this->clear && $this->initialized) {
            ($this->clear)($this->id, $this->value);
        }
    }
}
