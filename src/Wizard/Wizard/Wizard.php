<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard;

use Ruwork\Wizard\Exception\StepOutOfBoundsException;
use Ruwork\Wizard\Step\StepInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class Wizard implements \IteratorAggregate, WizardInterface
{
    private $accessor;
    private $options;
    private $steps;
    private $dataClearValues;
    private $data;
    private $sorted = false;
    private $stepsPaths;

    /**
     * @param StepInterface[] $steps
     */
    public function __construct(array $options, array $steps, array $dataClearValues, $data = null)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->options = $options;
        $this->steps = $steps;
        $this->dataClearValues = $dataClearValues;
        $this->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        $this->sort();

        return $this->steps;
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
    public function get(string $name): StepInterface
    {
        if (!$this->has($name)) {
            throw StepOutOfBoundsException::fromName($name);
        }

        return $this->steps[$name];
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

        foreach ($this->steps as $step) {
            if (null === $this->data) {
                $step->setData(null);
            } else {
                $step->setData($this->accessor->getValue($this->data, $step->getPath()));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        foreach ($this->steps as $step) {
            if (!$step->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function revalidate(): void
    {
        foreach ($this->steps as $step) {
            $step->revalidate();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function synchronize(): void
    {
        foreach ($this->steps as $step) {
            $this->accessor->setValue($this->data, $step->getPath(), $step->getData());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        foreach ($this->dataClearValues as $clearPath => $value) {
            foreach ($this->getStepsPaths() as $stepPath) {
                if (0 === strpos($stepPath, $clearPath)) {
                    continue 2;
                }
            }

            try {
                $this->accessor->setValue($this->data, $clearPath, $value);
            } catch (\RuntimeException $exception) {
            }
        }
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
    public function getIterator(): \Traversable
    {
        $this->sort();

        yield from $this->steps;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->steps);
    }

    private function sort(): void
    {
        if ($this->sorted) {
            return;
        }

        uasort($this->steps, static function (StepInterface $a, StepInterface $b): int {
            return $a->getPosition() <=> $b->getPosition();
        });

        $this->sorted = true;
    }

    /**
     * @return string[]
     */
    private function getStepsPaths(): array
    {
        if (null !== $this->stepsPaths) {
            return $this->stepsPaths;
        }

        return $this->stepsPaths = array_map(static function (StepInterface $step): string {
            return $step->getPath();
        }, $this->steps);
    }
}
