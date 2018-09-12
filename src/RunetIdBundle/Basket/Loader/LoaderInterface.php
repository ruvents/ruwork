<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Loader;

use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;
use Ruwork\RunetIdBundle\Basket\Basket\PayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface LoaderInterface
{
    public static function getClass(): string;

    /**
     * @return object
     */
    public function load(array $options, BasketInterface $basket, PayCollection $collection);

    public function configureOptions(OptionsResolver $resolver): void;
}
