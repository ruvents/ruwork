<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\TypeResolver;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Ruwork\ObjectStore\Type\StoreTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ResolvedStoreType implements ResolvedStoreTypeInterface
{
    private $name;
    private $type;
    private $requiredTypes;
    private $optionsResolver;

    /**
     * @param StoreTypeInterface[] $requiredTypes
     */
    public function __construct(string $name, StoreTypeInterface $type, array $requiredTypes)
    {
        $this->name = $name;
        $this->type = $type;
        $this->requiredTypes = $requiredTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): StoreTypeInterface
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredTypes(): iterable
    {
        return $this->requiredTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver(): OptionsResolver
    {
        if (null !== $this->optionsResolver) {
            return $this->optionsResolver;
        }

        $resolver = new OptionsResolver();

        foreach ($this->requiredTypes as $requiredType) {
            $requiredType->configureOptions($resolver);
        }

        $this->type->configureOptions($resolver);

        return $this->optionsResolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void
    {
        foreach ($this->requiredTypes as $requiredType) {
            $requiredType->configureStore($configurator, $options);
        }

        $this->type->configureStore($configurator, $options);
    }
}
