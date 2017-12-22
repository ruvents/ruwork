<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Validator;

use Ruwork\DoctrineBehaviorsBundle\Multilingual\MultilingualInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidMultilingualValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidMultilingual) {
            throw new UnexpectedTypeException($constraint, ValidMultilingual::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof MultilingualInterface) {
            throw new UnexpectedTypeException($value, MultilingualInterface::class);
        }

        foreach ($constraint->locales as $locale => $constraints) {
            $this->context
                ->getValidator()
                ->inContext($this->context)
                ->atPath($locale)
                ->validate($value->get($locale), $constraints);
        }
    }
}
