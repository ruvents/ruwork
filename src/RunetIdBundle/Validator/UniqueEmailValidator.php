<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Validator;

use Ruwork\RunetIdBundle\Client\RunetIdClients;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueEmailValidator extends ConstraintValidator
{
    private $clients;

    public function __construct(RunetIdClients $clients)
    {
        $this->clients = $clients;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        if (null !== $value &&
            !\is_scalar($value) &&
            !(\is_object($value) && \method_exists($value, '__toString'))
        ) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if ('' === $value) {
            return;
        }

        if ($this->emailExists($constraint, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(UniqueEmail::NOT_UNIQUE_ERROR)
                ->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING | self::PRETTY_DATE))
                ->addViolation();
        }
    }

    private function emailExists(UniqueEmail $constraint, string $email): bool
    {
        $client = $this->clients->get($constraint->client);

        $users = $client
            ->userSearch()
            ->setEventId($constraint->eventId)
            ->setVisible($constraint->visible)
            ->setQuery($email)
            ->getResult()
            ->Users;

        return \iterator_count($users) > 0;
    }
}
