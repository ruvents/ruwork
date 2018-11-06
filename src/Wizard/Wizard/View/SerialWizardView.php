<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard\View;

use Ruwork\Wizard\Exception\BadMethodCallException;
use Ruwork\Wizard\Exception\RuntimeException;
use Ruwork\Wizard\Step\View\SerialStepView;
use Ruwork\Wizard\Wizard\WizardInterface;

final class SerialWizardView implements \ArrayAccess, \Countable, \IteratorAggregate
{
    private $steps;
    private $current;

    /**
     * @param SerialStepView[] $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    public static function fromWizard(WizardInterface $wizard, string $current): self
    {
        $steps = [];
        $passedInvalid = false;

        foreach ($wizard->all() as $name => $step) {
            $steps[$name] = SerialStepView::fromStep($step, $current === $name, $passedInvalid);
            $passedInvalid = $passedInvalid || !$step->isValid();
        }

        return new self($steps);
    }

    public function getCurrent(): SerialStepView
    {
        if (null !== $this->current) {
            return $this->current;
        }

        foreach ($this->steps as $step) {
            if ($step->isCurrent()) {
                return $this->current = $step;
            }
        }

        throw new RuntimeException('No current step.');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->steps[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @return SerialStepView
     */
    public function offsetGet($offset)
    {
        return $this->steps[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException();
    }

    /**
     * {@inheritdoc}
     *
     * @return \Generator|SerialStepView[]
     */
    public function getIterator()
    {
        yield from $this->steps;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->steps);
    }
}
