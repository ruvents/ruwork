<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Provider;

class IterableProvider implements ProviderInterface
{
    private $data;

    public function __construct(iterable $data)
    {
        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
        }

        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(int $offset, int $limit): iterable
    {
        return array_slice($this->data, $offset, $limit);
    }
}
