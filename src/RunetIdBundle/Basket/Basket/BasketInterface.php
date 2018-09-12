<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket;

interface BasketInterface
{
    public function getPayerRunetId(): int;

    /**
     * @return object
     */
    public function load(string $class, array $options = []);

    public function handle(string $handler, array $options = []): void;
}
