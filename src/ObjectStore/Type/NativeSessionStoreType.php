<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Type;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Ruwork\ObjectStore\Storage\NativeSessionStorage;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NativeSessionStoreType implements StoreTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getRequiredTypes(): iterable
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('session_key')
            ->setAllowedTypes('session_key', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void
    {
        $configurator->setStorage(new NativeSessionStorage($options['session_key']));
    }
}
