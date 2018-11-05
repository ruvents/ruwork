<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\View;

use Ruwork\Wizard\Step\StepInterface;

final class SerialStepView
{
    private $name;
    private $data;
    private $options;
    private $valid;
    private $current;
    private $disabled;

    public function __construct(
        string $name,
        $data,
        array $options,
        bool $valid,
        bool $current,
        bool $disabled
    ) {
        $this->name = $name;
        $this->data = $data;
        $this->options = $options;
        $this->valid = $valid;
        $this->current = $current;
        $this->disabled = $disabled;
    }

    public static function fromStep(StepInterface $step, bool $current, bool $disabled): self
    {
        return new self(
            $step->getName(),
            $step->getData(),
            $step->getOptions(),
            $step->isValid(),
            $current,
            $disabled
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isCurrent(): bool
    {
        return $this->current;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }
}
