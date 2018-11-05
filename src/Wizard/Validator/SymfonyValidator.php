<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

final class SymfonyValidator implements ValidatorInterface
{
    private $validator;
    private $constraints;
    private $groups;

    public function __construct(SymfonyValidatorInterface $validator, $constraints = null, $groups = null)
    {
        $this->validator = $validator;
        $this->constraints = $constraints;
        $this->groups = $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($data): bool
    {
        return 0 === $this->validator->validate($data, $this->constraints, $this->groups)->count();
    }
}
