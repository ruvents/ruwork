<?php

declare(strict_types=1);

namespace Ruwork\Paginator;

class Paginator implements \IteratorAggregate, \Countable
{
    private $sections;
    private $total;
    private $items;
    private $totalItems;
    private $current;

    /**
     * @param Section[] $sections
     */
    public function __construct(array $sections, int $total, iterable $items, int $totalItems, int $current)
    {
        $this->sections = $sections;
        $this->total = $total;
        $this->items = $items;
        $this->totalItems = $totalItems;
        $this->current = $current;
    }

    /**
     * The total number of pages.
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    public function getPrevious(): ?Page
    {
        if ($this->current > 1) {
            return $this->createPage($this->current - 1);
        }

        return null;
    }

    public function getCurrent(): Page
    {
        return $this->createPage($this->current);
    }

    public function getNext(): ?Page
    {
        if ($this->current < $this->total) {
            return $this->createPage($this->current + 1);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Traversable|Section[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->sections);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->sections);
    }

    private function createPage(int $number)
    {
        return new Page($number, $this->current === $number);
    }
}
