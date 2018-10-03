<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Item;

final class Item implements ItemInterface
{
    private $id;
    private $data;

    public function __construct(string $id, $data)
    {
        $this->id = $id;
        $this->data = $data;
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
        return $this->data;
    }
}
