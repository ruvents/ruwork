<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class CompositeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AbstractCompositeConstraint) {
            throw new UnexpectedTypeException($constraint, AbstractCompositeConstraint::class);
        }

        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->validate($value, $constraint->constraints);
    }
}
