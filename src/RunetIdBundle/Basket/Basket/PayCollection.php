<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\ItemsResult;
use RunetId\Client\Result\Pay\ListResult;
use RunetId\Client\Result\Pay\OrderResult;

final class PayCollection
{
    private $client;
    private $payList;
    private $payItems = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array[]|\Generator yields tuples [ItemResult, ?OrderResult]
     */
    public function iterateItemsAndOrders(?int $ownerRunetId = null): \Generator
    {
        $items = [];

        foreach ($this->getPayList()->Items as $item) {
            yield [$item, null];

            $items[$item->Id] = true;
        }

        foreach ($this->getPayList()->Orders as $order) {
            foreach ($order->Items as $item) {
                if (isset($items[$item->Id])) {
                    continue;
                }

                yield [$item, $order];

                $items[$item->Id] = true;
            }
        }

        if (null !== $ownerRunetId) {
            foreach ($this->getPayItems($ownerRunetId)->Items as $item) {
                if (isset($items[$item->Id])) {
                    continue;
                }

                yield [$item, null];
            }
        }
    }

    /**
     * @return array tuples [ItemResult, ?OrderResult]
     */
    public function findPriorityItemsAndOrders(callable $filter, ?int $ownerRunetId = null): array
    {
        $prioritized = [];

        /** @var ItemResult $item */
        foreach ($this->iterateItemsAndOrders($ownerRunetId) as [$item, $order]) {
            $priority = $filter($item, $order);

            if (null === $priority) {
                continue;
            }

            $prioritized[] = [$item, $order, $priority];
        }

        \usort($prioritized, function (array $a, array $b): int {
            return $b[2] <=> $a[2];
        });

        return $prioritized;
    }

    /**
     * @return array a tuple [?ItemResult, ?OrderResult]
     */
    public function findPriorityItemAndOrder(callable $filter, ?int $ownerRunetId = null): array
    {
        $prioritized = $this->findPriorityItemsAndOrders($filter, $ownerRunetId);

        return \reset($prioritized) ?: [null, null];
    }

    /**
     * @return OrderResult[]
     */
    public function getOrders(): array
    {
        return $this->getPayList()->Orders;
    }

    private function getPayList(): ListResult
    {
        if (null === $this->payList) {
            $this->payList = $this->client->payList()->getResult();
        }

        return $this->payList;
    }

    private function getPayItems(?int $ownerRunetId = null): ItemsResult
    {
        if (null === $ownerRunetId) {
            $ownerRunetId = $this->client->getPayerRunetId();
        }

        if (!isset($this->payItems[$ownerRunetId])) {
            $this->payItems[$ownerRunetId] = $this->client
                ->payItems()
                ->setOwnerRunetId($ownerRunetId)
                ->getResult();
        }

        return $this->payItems[$ownerRunetId];
    }
}
