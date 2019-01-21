<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints;

use PHPUnit\Framework\MockObject\MockObject;
use Ruwork\ValidatorConstraints\Constraints\AbstractCompositeConstraint;
use Ruwork\ValidatorConstraints\Constraints\CompositeValidator;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class CompositeValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidateSkipsNull(): void
    {
        $constraint = $this->createConstraint();

        /** @var MockObject $contextualValidator */
        $contextualValidator = $this->context
            ->getValidator()
            ->inContext($this->context);
        $contextualValidator
            ->expects($this->never())
            ->method('validate');

        $this->validator->validate(null, $constraint);

        $this->assertNoViolation();
    }

    public function testValidate(): void
    {
        $innerConstraints = [new NotNull(), new IsTrue()];
        $constraint = $this->createConstraint($innerConstraints);
        $value = new \stdClass();

        /** @var MockObject $contextualValidator */
        $contextualValidator = $this->context
            ->getValidator()
            ->inContext($this->context);
        $contextualValidator
            ->expects($this->at(0))
            ->method('validate')
            ->with($value, $innerConstraints);

        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public function testValidateThrowsOnInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->createValidator()->validate(null, new NotNull());
    }

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new CompositeValidator();
    }

    private function createConstraint(array $constraints = [], $options = null): AbstractCompositeConstraint
    {
        return new class ($constraints, $options) extends AbstractCompositeConstraint
        {
            private $innerConstraints;

            public function __construct(array $innerConstraints=[], $options = null)
            {
                $this->innerConstraints = $innerConstraints;
                parent::__construct($options);
            }

            protected function getConstraints(): iterable
            {
                return $this->innerConstraints;
            }
        };
    }
}
