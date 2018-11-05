<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\Builder;

use Ruwork\Wizard\Step\StepInterface;

interface WizardConfiguratorInterface extends \Traversable, \Countable
{
    public function getOptions(): array;

    public function getData();

    public function setData($data): self;

    /**
     * @return StepInterface[]
     */
    public function all(): array;

    public function has(string $name): bool;

    public function get(string $name): StepInterface;

    public function add(string $name, string $type, array $options = []): self;

    public function register(StepInterface $step): self;

    public function remove(string $name): self;

    public function getDataClearValues(): array;

    public function addDataClearValue(string $path, $value = null): self;

    public function setDataClearValues(array $values): self;
}
