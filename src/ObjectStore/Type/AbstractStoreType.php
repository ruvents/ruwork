<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Type;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractStoreType implements StoreTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getRequiredTypes(): iterable
    {
        return [
            BaseStoreType::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void
    {
    }
}
