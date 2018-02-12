<?php

namespace Ruvents\RuworkBundle\Tests\Validator\Constraints;

use Ruvents\RuworkBundle\Validator\Constraints\Alias;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AliasValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @dataProvider getValidAliases
     */
    public function testValidAliases($alias)
    {
        $this->validator->validate($alias, new Alias());

        $this->assertNoViolation();
    }

    public function getValidAliases()
    {
        return [
            ['awdasd'],
            ['asd-asd-asd'],
            ['a123'],
        ];
    }

    /**
     * @dataProvider getInvalidAliases
     */
    public function testInvalidAliases($alias)
    {
        $constraint = new Alias([
            'message' => 'myMessage',
        ]);

        $this->validator->validate($alias, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$alias.'"')
            ->setCode(Regex::REGEX_FAILED_ERROR)
            ->assertRaised();
    }

    public function getInvalidAliases()
    {
        return [
            ['_awd'],
            ['фвцв'],
        ];
    }

    protected function createValidator()
    {
        return new RegexValidator();
    }
}
