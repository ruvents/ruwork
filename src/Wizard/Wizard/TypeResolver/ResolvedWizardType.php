<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\TypeResolver;

use Ruwork\Wizard\Wizard\Builder\WizardConfiguratorInterface;
use Ruwork\Wizard\Wizard\Type\WizardTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ResolvedWizardType implements ResolvedWizardTypeInterface
{
    private $name;
    private $type;
    private $requiredTypes;
    private $optionsResolver;

    /**
     * @param WizardTypeInterface[] $requiredTypes
     */
    public function __construct(string $name, WizardTypeInterface $type, array $requiredTypes)
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
    public function getType(): WizardTypeInterface
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
        $resolver->setDefault('data', null);

        foreach ($this->requiredTypes as $requiredType) {
            $requiredType->configureOptions($resolver);
        }

        $this->type->configureOptions($resolver);

        return $this->optionsResolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function configureWizard(WizardConfiguratorInterface $configurator, array $options): void
    {
        foreach ($this->requiredTypes as $requiredType) {
            $requiredType->configureWizard($configurator, $options);
        }

        $this->type->configureWizard($configurator, $options);
    }
}
