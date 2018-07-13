<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Validator;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use RunetId\Client\RunetIdClientFactory;
use Ruwork\RunetIdBundle\Fixtures\StringObject;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Webthink\Container\SimpleContainer;

/**
 * @internal
 */
class UniqueEmailValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var Client
     */
    private $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new Client();
        parent::setUp();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new UniqueEmail());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new UniqueEmail());

        $this->assertNoViolation();
    }

    public function testToStringIsValid(): void
    {
        $this->validator->validate(new StringObject(''), new UniqueEmail());

        $this->assertNoViolation();
    }

    public function testValid(): void
    {
        $this->setUsers([]);

        $this->validator->validate('mail@mail.com', new UniqueEmail());

        $this->assertNoViolation();
    }

    public function testInvalid(): void
    {
        $email = 'mail@mail.com';

        $this->setUsers([[
            'Id' => 1,
        ]]);

        $constraint = new UniqueEmail([
            'message' => 'myMessage',
        ]);

        $this->validator->validate($email, $constraint);

        $this->buildViolation('myMessage')
            ->setCode(UniqueEmail::NOT_UNIQUE_ERROR)
            ->setParameter('{{ value }}', '"'.$email.'"')
            ->assertRaised();
    }

    public function testUniqueEmailConstraintExpected(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate(null, new NotBlank());
    }

    public function testExpectsStringCompatibleType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate(new \stdClass(), new UniqueEmail());
    }

    protected function createValidator()
    {
        $this->httpClient = new Client();
        $factory = new RunetIdClientFactory($this->httpClient);
        $client = $factory->create('key', 'secret');

        return new UniqueEmailValidator(new SimpleContainer(['default' => $client]));
    }

    private function setUsers(array $data): void
    {
        $this->httpClient->addResponse(new Response(200, [], \json_encode([
            'Users' => $data,
        ])));
    }
}
