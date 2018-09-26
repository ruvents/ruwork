<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

final class ManagerFilter extends AbstractBinaryFilter
{
    private $manager;

    public function __construct(string $manager, int $priority = 1)
    {
        $this->manager = $manager;
        parent::__construct($priority);
    }

    /**
     * {@inheritdoc}
     */
    protected function vote(ItemResult $item, ?OrderResult $order): bool
    {
        return $this->manager === $item->Product->Manager;
    }
}
