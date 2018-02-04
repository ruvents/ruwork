<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Validator;

use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueEmailValidator extends ConstraintValidator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        if (null !== $value && !is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if ('' === $value) {
            return;
        }

        if ($this->emailExists($constraint->client, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(UniqueEmail::NOT_UNIQUE_ERROR)
                ->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING | self::PRETTY_DATE))
                ->addViolation();
        }
    }

    private function emailExists(string $client, string $email): bool
    {
        $users = $this->container
            ->get($client)
            ->userSearch()
            ->setQuery($email)
            ->getResult()
            ->Users;

        foreach ($users as $user) {
            if ($email === $user->Email) {
                return true;
            }
        }

        return false;
    }
}
