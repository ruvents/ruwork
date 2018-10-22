<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Type;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface StoreTypeInterface
{
    /**
     * @return string[]
     */
    public static function getRequiredTypes(): iterable;

    public function configureOptions(OptionsResolver $resolver): void;

    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void;
}
