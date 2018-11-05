<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard;

use Ruwork\Wizard\Exception\StepOutOfBoundsException;
use Ruwork\Wizard\Step\StepInterface;

interface WizardInterface extends \Traversable, \Countable
{
    /**
     * @return StepInterface[]
     */
    public function all(): array;

    public function has(string $name): bool;

    /**
     * @throws StepOutOfBoundsException
     */
    public function get(string $name): StepInterface;

    public function getData();

    public function setData($data): void;

    public function isValid(): bool;

    public function revalidate(): void;

    public function synchronize(): void;

    public function clear(): void;

    public function getOptions(): array;
}
