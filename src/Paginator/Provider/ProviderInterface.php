<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Provider;

interface ProviderInterface
{
    /**
     * The total number of items.
     */
    public function getTotal(): int;

    /**
     * Items for the current page.
     *
     * @param int $offset the zero-based index of the first item
     * @param int $limit  the current page limit (actually equals the $perPage value)
     */
    public function getItems(int $offset, int $limit): iterable;
}
