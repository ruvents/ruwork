<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Factory;

use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;

interface BasketFactoryInterface
{
    public function create(int $payerRunetId, ?string $clientName = null): BasketInterface;
}
