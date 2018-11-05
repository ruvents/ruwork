<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Builder;

use Ruwork\Wizard\FormFactory\FormFactoryInterface;
use Ruwork\Wizard\Step\Step;
use Ruwork\Wizard\Step\StepInterface;
use Ruwork\Wizard\Validator\ValidatorInterface;

final class StepBuilder implements StepConfiguratorInterface
{
    private $name;
    private $options;
    private $path;
    private $position = 0;
    private $validator;
    private $formFactory;

    public function __construct(string $name, array $options)
    {
        $this->name = $name;
        $this->options = $options;
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
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(?string $path): StepConfiguratorInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(int $position): StepConfiguratorInterface
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(): ?ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidator(?ValidatorInterface $validator): StepConfiguratorInterface
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormFactory(): ?FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormFactory(?FormFactoryInterface $formFactory): StepConfiguratorInterface
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): StepInterface
    {
        return new Step(
            $this->name,
            $this->options,
            $this->position,
            $this->path,
            $this->validator,
            $this->formFactory
        );
    }
}
