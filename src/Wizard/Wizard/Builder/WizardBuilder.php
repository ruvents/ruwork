<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\Builder;

use Ruwork\Wizard\Exception\StepOutOfBoundsException;
use Ruwork\Wizard\Step\Factory\StepFactoryInterface;
use Ruwork\Wizard\Step\StepInterface;
use Ruwork\Wizard\Wizard\Wizard;
use Ruwork\Wizard\Wizard\WizardInterface;

final class WizardBuilder implements \IteratorAggregate, WizardConfiguratorInterface
{
    private $stepFactory;
    private $options;
    private $data;
    private $steps = [];
    private $dataClearValues = [];

    public function __construct(StepFactoryInterface $stepFactory, array $options, $data = null)
    {
        $this->stepFactory = $stepFactory;
        $this->options = $options;
        $this->data = $data;
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
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data): WizardConfiguratorInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
    {
        return isset($this->steps[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return iterator_to_array($this);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name): StepInterface
    {
        if (!isset($this->steps[$name])) {
            throw StepOutOfBoundsException::fromName($name);
        }

        $step = $this->steps[$name];

        if ($step instanceof StepInterface) {
            return $step;
        }

        $step = $this->stepFactory->create($name, $step[0], $step[1]);
        $this->register($step);

        return $step;
    }

    /**
     * {@inheritdoc}
     */
    public function add(string $name, string $type, array $options = []): WizardConfiguratorInterface
    {
        $this->steps[$name] = [$type, $options];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function register(StepInterface $step): WizardConfiguratorInterface
    {
        $this->steps[$step->getName()] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $name): WizardConfiguratorInterface
    {
        unset($this->steps[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataClearValues(): array
    {
        return $this->dataClearValues;
    }

    /**
     * {@inheritdoc}
     */
    public function addDataClearValue(string $path, $value = null): WizardConfiguratorInterface
    {
        $this->dataClearValues[$path] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataClearValues(array $values): WizardConfiguratorInterface
    {
        $this->dataClearValues = $values;

        return $this;
    }

    public function build(): WizardInterface
    {
        return new Wizard($this->options, $this->all(), $this->dataClearValues, $this->data);
    }

    /**
     * {@inheritdoc}
     *
     * @return StepInterface[]|\Traversable
     */
    public function getIterator()
    {
        foreach (array_keys($this->steps) as $name) {
            yield $name => $this->get($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->steps);
    }
}
