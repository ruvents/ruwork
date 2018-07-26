<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface LoaderInterface
{
    /**
     * @return object
     */
    public function load(string $class, array $options, Basket $basket);

    public function configureOptions(OptionsResolver $resolver): void;

    public function supportsLoading(string $class): bool;
}
