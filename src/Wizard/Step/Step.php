<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step;

use Ruwork\Wizard\FormFactory\FormFactoryInterface;
use Ruwork\Wizard\FormFactory\NullFormFactory;
use Ruwork\Wizard\Validator\NullValidator;
use Ruwork\Wizard\Validator\ValidatorInterface;

final class Step implements StepInterface
{
    private $name;
    private $path;
    private $options;
    private $position;
    private $validator;
    private $formFactory;
    private $data;
    private $valid;

    public function __construct(
        string $name,
        array $options,
        int $position,
        ?string $path = null,
        ?ValidatorInterface $validator = null,
        ?FormFactoryInterface $formFactory = null
    ) {
        $this->name = $name;
        $this->path = $path ?? $name;
        $this->options = $options;
        $this->position = $position;
        $this->validator = $validator ?? new NullValidator();
        $this->formFactory = $formFactory ?? new NullFormFactory();
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
    public function getPath(): string
    {
        return $this->path;
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
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data): void
    {
        $this->data = $data;
        $this->revalidate();
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if (null !== $this->valid) {
            return $this->valid;
        }

        return $this->valid = $this->validator->isValid($this->getData());
    }

    /**
     * {@inheritdoc}
     */
    public function revalidate(): void
    {
        $this->valid = null;
    }

    /**
     * {@inheritdoc}
     */
    public function createForm()
    {
        return $this->formFactory->create($this->getData(), [$this, 'setData']);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
