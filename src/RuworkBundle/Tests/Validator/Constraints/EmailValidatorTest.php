<?php

namespace Ruvents\RuworkBundle\Tests\Validator\Constraints;

use Egulias\EmailValidator\Exception\AtextAfterCFWS;
use Egulias\EmailValidator\Exception\NoDomainPart;
use Ruvents\RuworkBundle\Validator\Constraints\Email;
use Ruvents\RuworkBundle\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EmailValidatorTest extends ConstraintValidatorTestCase
{
    public function testNullIsValid()
    {
        $this->validator->validate(null, new Email());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Email());

        $this->assertNoViolation();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testExpectsStringCompatibleType()
    {
        $this->validator->validate(new \stdClass(), new Email());
    }

    /**
     * @dataProvider getValidEmails
     */
    public function testValidEmails($email)
    {
        $this->validator->validate($email, new Email());

        $this->assertNoViolation();
    }

    public function getValidEmails()
    {
        return [
            ['fabien@symfony.com'],
            ['example@example.co.uk'],
            ['fabien_potencier@example.fr'],
        ];
    }

    /**
     * @dataProvider getInvalidEmails
     */
    public function testInvalidEmails($email, $code)
    {
        $constraint = new Email([
            'message' => 'myMessage',
        ]);

        $this->validator->validate($email, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$email.'"')
            ->setCode($code)
            ->assertRaised();
    }

    public function getInvalidEmails()
    {
        return [
            ['example', NoDomainPart::CODE],
            ['example@', NoDomainPart::CODE],
            ['foo@example.com bar', AtextAfterCFWS::CODE],
        ];
    }

    public function testStrict()
    {
        $constraint = new Email();

        $this->validator->validate('example@localhost', $constraint);

        $this->assertNoViolation();
    }

    protected function createValidator()
    {
        return new EmailValidator();
    }
}
