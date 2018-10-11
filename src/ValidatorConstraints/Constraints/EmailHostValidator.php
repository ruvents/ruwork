<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class EmailHostValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EmailHost) {
            throw new UnexpectedTypeException($value, EmailHost::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_scalar($value) && !(\is_object($value) && \method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $host = $this->extractHost((string) $value);

        if (null === $host) {
            return;
        }

        $normalizedHost = $this->normalizeHost($host);
        $types = (array) $constraint->types;

        foreach ($types as $type) {
            if (\checkdnsrr($normalizedHost, $type)) {
                return;
            }
        }

        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $this->formatValue($host))
            ->setParameter('{{ types }}', \implode(', ', $types))
            ->addViolation();
    }

    private function extractHost(string $value): ?string
    {
        if (\preg_match('/@(.+)$/', $value, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function normalizeHost(string $host): string
    {
        if ('.' !== \mb_substr($host, -1)) {
            $host .= '.';
        }

        return \idn_to_ascii($host);
    }
}
