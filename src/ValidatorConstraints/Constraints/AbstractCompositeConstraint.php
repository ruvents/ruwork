<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraints\Composite;

abstract class AbstractCompositeConstraint extends Composite
{
    public $constraints = [];

    /**
     * {@inheritdoc}
     */
    final public function validatedBy()
    {
        return CompositeValidator::class;
    }

    /**
     * {@inheritdoc}
     */
    final protected function getCompositeOption()
    {
        return 'constraints';
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeNestedConstraints()
    {
        foreach ($this->getConstraints() as $constraint) {
            $this->constraints[] = $constraint;
        }
    }

    abstract protected function getConstraints(): iterable;
}
