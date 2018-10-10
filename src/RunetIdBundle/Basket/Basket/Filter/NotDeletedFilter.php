<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

final class NotDeletedFilter extends AbstractBinaryFilter
{
    /**
     * {@inheritdoc}
     */
    protected function vote(ItemResult $item, ?OrderResult $order): bool
    {
        return !$item->Deleted;
    }
}
