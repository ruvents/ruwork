<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints;

use PHPUnit\Framework\MockObject\MockObject;
use Ruwork\ValidatorConstraints\Constraints\AtPath;
use Ruwork\ValidatorConstraints\Constraints\AtPathValidator;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @internal
 */
final class AtPathValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidateSkipsNull(): void
    {
        /** @var MockObject $contextualValidator */
        $contextualValidator = $this->context
            ->getValidator()
            ->inContext($this->context);
        $contextualValidator
            ->expects($this->never())
            ->method('validate');

        $this->validator->validate(null, new AtPath([
            'path' => 'path',
            'constraints' => [],
        ]));

        $this->assertNoViolation();
    }

    public function testValidate(): void
    {
        $innerConstraints = [new NotNull(), new IsTrue()];
        $constraint = new AtPath([
            'path' => '[a]',
            'constraints' => $innerConstraints,
        ]);

        $this->expectValidateValueAt(0, '[a]', 1, $innerConstraints);

        $this->validator->validate(['a' => 1], $constraint);

        $this->assertNoViolation();
    }

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new AtPathValidator();
    }
}
