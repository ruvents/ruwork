<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CompositeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Composite) {
            throw new UnexpectedTypeException($constraint, Composite::class);
        }

        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->validate($value, $constraint->constraints);
    }
}
