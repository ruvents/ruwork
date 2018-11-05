<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step;

interface StepInterface
{
    public function getName(): string;

    public function getPath(): string;

    public function getPosition(): int;

    public function getData();

    public function setData($data): void;

    public function isValid(): bool;

    public function revalidate(): void;

    public function createForm();

    public function getOptions(): array;
}
