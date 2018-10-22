<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Type;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BaseStoreType implements StoreTypeInterface
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
            ->setRequired('class')
            ->setDefault('default', static function (Options $options): callable {
                $class = $options['class'];

                return static function () use ($class) {
                    return new $class();
                };
            })
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('default', ['null', 'object', 'callable'])
            ->setNormalizer('default', static function (Options $options, $default): callable {
                if (\is_callable($default)) {
                    return $default;
                }

                return static function () use ($default) {
                    return $default;
                };
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void
    {
        $configurator
            ->setClass($options['class'])
            ->setDefaultFactory($options['default']);
    }
}
