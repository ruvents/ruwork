<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Validator;

final class NullValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function isValid($data): bool
    {
        return false;
    }
}
