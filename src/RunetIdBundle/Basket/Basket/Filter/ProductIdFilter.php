<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

final class ProductIdFilter extends AbstractBinaryFilter
{
    private $id;

    public function __construct(int $id, int $priority = 0)
    {
        $this->id = $id;
        parent::__construct($priority);
    }

    /**
     * {@inheritdoc}
     */
    protected function vote(ItemResult $item, ?OrderResult $order): bool
    {
        return $this->id === $item->Product->Id;
    }
}
