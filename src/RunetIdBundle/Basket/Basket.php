<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket;

use RunetId\Client\Result\Pay\CouponResult;
use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\ItemsResult;
use RunetId\Client\Result\Pay\ListResult;
use RunetId\Client\Result\Pay\OrderResult;
use RunetId\Client\RunetIdClient;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Basket
{
    private $client;
    private $payerRunetId;
    private $loaders;
    private $handlers;
    private $loadedObjects;
    private $handledObjects;
    private $attributes = [];
    private $payList;
    private $payItems;

    /**
     * @param LoaderInterface[]  $loaders
     * @param HandlerInterface[] $handlers
     */
    public function __construct(
        RunetIdClient $client,
        int $payerRunetId,
        iterable $loaders,
        iterable $handlers
    ) {
        $this->client = $client;
        $this->payerRunetId = $payerRunetId;
        $this->loaders = $loaders;
        $this->handlers = $handlers;
        $this->loadedObjects = new \SplObjectStorage();
        $this->handledObjects = new \SplObjectStorage();
    }

    public function getClient(): RunetIdClient
    {
        return $this->client;
    }

    public function getPayerRunetId(): int
    {
        return $this->payerRunetId;
    }

    /**
     * @return object
     */
    public function load(string $class, array $options = [])
    {
        if (\count($this->handledObjects) > 0) {
            throw new \RuntimeException('Loading is forbidden after handling has started.');
        }

        $loader = $this->getLoader($class);
        $resolver = new OptionsResolver();
        $loader->configureOptions($resolver);
        $options = $resolver->resolve($options);
        $object = $loader->load($class, $options, $this);

        if (!$object instanceof $class) {
            throw new \UnexpectedValueException(\sprintf(
                'Loaded object must be an instance of %s.',
                $class
            ));
        }

        $this->loadedObjects->attach($object);

        return $object;
    }

    public function handle($object): void
    {
        if (!$this->loadedObjects->contains($object)) {
            throw new \RuntimeException(\sprintf('This object (%s) was never loaded.', \get_class($object)));
        }

        if ($this->handledObjects->contains($object)) {
            throw new \RuntimeException(\sprintf('This object (%s) was already handled.', \get_class($object)));
        }

        $this->handledObjects->attach($object);
        $this->getHandler($object)->handle($object, $this);
    }

    public function getPayList(): ListResult
    {
        if (null === $this->payList) {
            $this->payList = $this->client
                ->payList()
                ->setPayerRunetId($this->payerRunetId)
                ->getResult();
        }

        return $this->payList;
    }

    public function getPayItems(): ItemsResult
    {
        if (null === $this->payItems) {
            $this->payItems = $this->client
                ->payItems()
                ->setOwnerRunetId($this->payerRunetId)
                ->getResult();
        }

        return $this->payItems;
    }

    /**
     * @return array[]|\Generator yields tuples [ItemResult, ?OrderResult]
     */
    public function iterateItemsAndOrders(bool $withPayItems = true): \Generator
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

        if ($withPayItems) {
            foreach ($this->getPayItems()->Items as $item) {
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
    public function findPriorityItemsAndOrders(callable $filter, bool $withPayItems = true): array
    {
        $prioritized = [];

        /** @var ItemResult $item */
        foreach ($this->iterateItemsAndOrders($withPayItems) as [$item, $order]) {
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
    public function findPriorityItemAndOrder(callable $filter, bool $withPayItems = true): array
    {
        $prioritized = $this->findPriorityItemsAndOrders($filter, $withPayItems);

        return \reset($prioritized) ?: [null, null];
    }

    /**
     * @return OrderResult[]
     */
    public function getOrders(): array
    {
        return $this->getPayList()->Orders;
    }

    public function addItem(int $ownerRunetId, int $productId, array $attributes = []): ItemResult
    {
        return $this->client
            ->payAdd()
            ->setPayerRunetId($this->payerRunetId)
            ->setOwnerRunetId($ownerRunetId)
            ->setProductId($productId)
            ->setAttributes($attributes)
            ->getResult();
    }

    public function deleteItem(int $id): bool
    {
        return $this->client
            ->payDelete()
            ->setPayerRunetId($this->payerRunetId)
            ->setOrderItemId($id)
            ->getResult()
            ->Success;
    }

    public function activateCoupon(int $ownerRunetId, string $coupon, ?int $productId = null): CouponResult
    {
        $endpoint = $this->client
            ->payCoupon()
            ->setPayerRunetId($this->payerRunetId)
            ->setOwnerRunetId($ownerRunetId)
            ->setCouponCode($coupon);

        if (null !== $productId) {
            $endpoint->setProductId($productId);
        }

        return $endpoint->getResult();
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function setAttribute(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    private function getLoader(string $class): LoaderInterface
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supportsLoading($class)) {
                return $loader;
            }
        }

        throw new \RuntimeException(\sprintf(
            'Failed to load object of class %s: no supporting loaders were found.',
            $class
        ));
    }

    private function getHandler($object): HandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supportsHandling($object)) {
                return $handler;
            }
        }

        throw new \RuntimeException(\sprintf(
            'Failed to handle object of class %s: no supporting handlers were found.',
            \get_class($object)
        ));
    }
}
