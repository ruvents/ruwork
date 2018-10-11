<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class SequenceValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $sequence)
    {
        if (!$sequence instanceof Sequence) {
            throw new UnexpectedTypeException($value, Sequence::class);
        }

        $validator = $this->context
            ->getValidator()
            ->inContext($this->context);

        foreach ($sequence->constraints as $constraint) {
            $validator->validate($value, $constraint);

            if ($validator->getViolations()->count() > 0) {
                return;
            }
        }
    }
}
