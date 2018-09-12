<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket;

use RunetId\Client\Endpoint\Pay\AddEndpoint;
use RunetId\Client\Endpoint\Pay\CouponEndpoint;
use RunetId\Client\Endpoint\Pay\DeleteEndpoint;
use RunetId\Client\Endpoint\Pay\ItemsEndpoint;
use RunetId\Client\Endpoint\Pay\ListEndpoint;
use RunetId\Client\RunetIdClient;

final class Client
{
    private $client;
    private $payerRunetId;

    public function __construct(RunetIdClient $client, int $payerRunetId)
    {
        $this->client = $client;
        $this->payerRunetId = $payerRunetId;
    }

    public function getClient(): RunetIdClient
    {
        return $this->client;
    }

    public function getPayerRunetId(): int
    {
        return $this->payerRunetId;
    }

    public function payList(): ListEndpoint
    {
        return $this->client
            ->payList()
            ->setPayerRunetId($this->payerRunetId);
    }

    public function payItems(): ItemsEndpoint
    {
        return $this->client
            ->payItems()
            ->setOwnerRunetId($this->payerRunetId);
    }

    public function payAdd(): AddEndpoint
    {
        return $this->client
            ->payAdd()
            ->setPayerRunetId($this->payerRunetId);
    }

    public function payDelete(): DeleteEndpoint
    {
        return $this->client
            ->payDelete()
            ->setPayerRunetId($this->payerRunetId);
    }

    public function payCoupon(): CouponEndpoint
    {
        return $this->client
            ->payCoupon()
            ->setPayerRunetId($this->payerRunetId);
    }
}
