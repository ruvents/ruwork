<?php

declare(strict_types=1);

namespace Ruwork\Paginator;

final class Section implements \IteratorAggregate, \Countable
{
    private $pages;
    private $last;

    /**
     * @param Page[] $pages
     */
    public function __construct(array $pages, bool $last)
    {
        $this->pages = $pages;
        $this->last = $last;
    }

    public function isLast(): bool
    {
        return $this->last;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Traversable|Page[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->pages);
    }
}
