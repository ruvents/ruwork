<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Builder;

use Ruwork\Wizard\FormFactory\FormFactoryInterface;
use Ruwork\Wizard\Validator\ValidatorInterface;

interface StepConfiguratorInterface
{
    public function getName(): string;

    public function getOptions(): array;

    public function getPath(): ?string;

    public function setPath(?string $path): self;

    public function getPosition(): int;

    public function setPosition(int $position): self;

    public function getValidator(): ?ValidatorInterface;

    public function setValidator(?ValidatorInterface $validator): self;

    public function getFormFactory(): ?FormFactoryInterface;

    public function setFormFactory(?FormFactoryInterface $formFactory): self;
}
