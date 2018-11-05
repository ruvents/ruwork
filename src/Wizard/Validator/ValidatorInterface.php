<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Validator;

interface ValidatorInterface
{
    public function isValid($data): bool;
}
