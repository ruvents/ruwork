<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\TypeResolver;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Ruwork\ObjectStore\Type\StoreTypeInterface;

interface ResolvedStoreTypeInterface
{
    public function getName(): string;

    public function getType(): StoreTypeInterface;

    /**
     * @return StoreTypeInterface[]
     */
    public function getRequiredTypes(): iterable;

    public function resolveOptions(array $options): array;

    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void;
}
