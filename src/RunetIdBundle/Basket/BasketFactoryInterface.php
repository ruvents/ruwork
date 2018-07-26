<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket;

interface BasketFactoryInterface
{
    public function create(int $payerRunetId): Basket;
}
