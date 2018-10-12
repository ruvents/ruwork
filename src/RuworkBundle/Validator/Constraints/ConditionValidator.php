<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

@\trigger_error(\sprintf('Class %s is deprecated since 0.12 and will be removed in 0.13. Use ruwork/validator-constraints package instead.', ConditionValidator::class), E_USER_DEPRECATED);

class ConditionValidator extends ConstraintValidator
{
    private $expressionLanguage;

    public function __construct(?ExpressionLanguage $expressionLanguage = null)
    {
        $this->expressionLanguage = $expressionLanguage ?? new ExpressionLanguage();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Condition) {
            throw new UnexpectedTypeException($constraint, Condition::class);
        }

        $condition = $this->expressionLanguage->evaluate($constraint->expression, [
            'value' => $value,
            'this' => $this->context->getObject(),
        ]);

        if ($condition) {
            $this->context->getValidator()
                ->inContext($this->context)
                ->validate($value, $constraint->constraints);
        }
    }
}
