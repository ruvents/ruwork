<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException as AccessorUnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class AtPathValidator extends ConstraintValidator
{
    private $accessor;

    public function __construct(?PropertyAccessorInterface $accessor = null)
    {
        $this->accessor = $accessor ?? PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AtPath) {
            throw new UnexpectedTypeException($constraint, AtPath::class);
        }

        try {
            $value = $this->accessor->getValue($value, $constraint->path);
        } catch (AccessException $exception) {
            switch ($constraint->onAccessException) {
                case AtPath::NULL:
                    $value = null;
                    break;
                case AtPath::IGNORE:
                    return;
                case AtPath::EXCEPTION:
                default:
                    throw $exception;
            }
        } catch (AccessorUnexpectedTypeException $exception) {
            switch ($constraint->onUnexpectedTypeException) {
                case AtPath::NULL:
                    $value = null;
                    break;
                case AtPath::IGNORE:
                    return;
                case AtPath::EXCEPTION:
                default:
                    throw $exception;
            }
        }

        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->atPath($constraint->path)
            ->validate($value, $constraint->constraints);
    }
}
