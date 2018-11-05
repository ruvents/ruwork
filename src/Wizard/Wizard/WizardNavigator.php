<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Wizard;

use Ruwork\Wizard\Exception\EmptyWizardException;
use Ruwork\Wizard\Exception\InvalidPrecedingStepException;
use Ruwork\Wizard\Exception\StepOutOfBoundsException;
use Ruwork\Wizard\Step\StepInterface;

final class WizardNavigator
{
    private $wizard;
    private $namesByIndex;
    private $indexesByName;

    public function __construct(WizardInterface $wizard)
    {
        $this->wizard = $wizard;
        $this->namesByIndex = array_keys($this->wizard->all());
        $this->indexesByName = array_flip($this->namesByIndex);
    }

    public function getWizard(): WizardInterface
    {
        return $this->wizard;
    }

    /**
     * @throws EmptyWizardException
     */
    public function getFirst(): StepInterface
    {
        $steps = $this->wizard->all();

        if (false !== $step = reset($steps)) {
            return $step;
        }

        throw EmptyWizardException::create();
    }

    /**
     * @throws EmptyWizardException
     */
    public function getLast(): StepInterface
    {
        $steps = $this->wizard->all();

        if (false !== $step = end($steps)) {
            return $step;
        }

        throw EmptyWizardException::create();
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function getIndex(string $name): int
    {
        if (isset($this->indexesByName[$name])) {
            return $this->indexesByName[$name];
        }

        throw StepOutOfBoundsException::fromName($name);
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function isOfIndex(string $name, int $index): bool
    {
        return $this->getIndex($name) === $index;
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function isFirst(string $name): bool
    {
        return $this->isOfIndex($name, 0);
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function isLast(string $name): bool
    {
        return $this->isOfIndex($name, $this->wizard->count() - 1);
    }

    public function hasIndex(int $index): bool
    {
        return isset($this->namesByIndex[$index]);
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function getByIndex(int $index): StepInterface
    {
        if (isset($this->namesByIndex[$index])) {
            return $this->wizard->get($this->namesByIndex[$index]);
        }

        throw StepOutOfBoundsException::fromIndex($index);
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function getRelativeTo(string $name, int $offset): ?StepInterface
    {
        $index = $this->getIndex($name);

        try {
            return $this->getByIndex($index + $offset);
        } catch (StepOutOfBoundsException $exception) {
            return null;
        }
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function getPreviousBefore(string $name): ?StepInterface
    {
        return $this->getRelativeTo($name, -1);
    }

    /**
     * @throws StepOutOfBoundsException
     */
    public function getNextAfter(string $name): ?StepInterface
    {
        return $this->getRelativeTo($name, 1);
    }

    /**
     * @throws EmptyWizardException|InvalidPrecedingStepException|StepOutOfBoundsException
     */
    public function resolveCurrent(?string $name = null): StepInterface
    {
        if (null !== $name) {
            $this->wizard->get($name);
        }

        foreach ($this->wizard->all() as $step) {
            if (null === $name) {
                return $step;
            }

            if ($name === $step->getName()) {
                return $step;
            }

            if (!$step->isValid()) {
                throw InvalidPrecedingStepException::fromStep($step);
            }
        }

        throw EmptyWizardException::create();
    }
}
