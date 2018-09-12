<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Handler;

use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;
use Ruwork\RunetIdBundle\Basket\Basket\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface HandlerInterface
{
    public function handle(array $options, BasketInterface $basket, Client $client): void;

    public function configureOptions(OptionsResolver $resolver): void;
}
