<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\TypeResolver;

use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Ruwork\Wizard\Step\Type\StepTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ResolvedStepType implements ResolvedStepTypeInterface
{
    private $name;
    private $type;
    private $requiredTypes;
    private $optionsResolver;

    /**
     * @param StepTypeInterface[] $requiredTypes
     */
    public function __construct(string $name, StepTypeInterface $type, array $requiredTypes)
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
    public function getType(): StepTypeInterface
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredTypes(): array
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
    public function configureStep(StepConfiguratorInterface $configurator, array $options): void
    {
        foreach ($this->requiredTypes as $requiredType) {
            $requiredType->configureStep($configurator, $options);
        }

        $this->type->configureStep($configurator, $options);
    }
}
